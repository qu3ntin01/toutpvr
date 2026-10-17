# ============================================================================
#  ToutPVR 1.0.0-dev.1 — image serveur (distribution compilée)
#  Construction :  docker build -t toutpvr/server:1.0.0-dev.1 .
#  Python de l'image : ARG PYTHON_VERSION (roues fournies pour : 3.11, 3.12).
#      docker build --build-arg PYTHON_VERSION=3.11 -t toutpvr/server:1.0.0-dev.1 .
#  Le paquet toutpvr est installé UNIQUEMENT depuis wheels/ (--no-index) ; ses dépendances viennent de PyPI.
#  Architecture : les roues fournies sont linux x86_64 ; pour arm64 utilisez les roues aarch64 de la version.
# ============================================================================
ARG PYTHON_VERSION=3.12
FROM python:${PYTHON_VERSION}-slim AS base

ENV PYTHONDONTWRITEBYTECODE=1 \
    TP_ADMIN_USER=admin \
    PYTHONUNBUFFERED=1 \
    PIP_NO_CACHE_DIR=1 \
    PIP_DISABLE_PIP_VERSION_CHECK=1 \
    TP_DATA_DIR=/data \
    TP_CONFIG=/data/config.yaml \
    TP_HOST=0.0.0.0 \
    TP_PORT=8080

# ffmpeg (enregistrement en copie de flux, HLS), bibliothèques OpenCV, fuseaux horaires, curl (diagnostic)
RUN sed -i 's|http://deb.debian.org|https://deb.debian.org|g' /etc/apt/sources.list.d/debian.sources 2>/dev/null || true \
    && apt-get update \
    && apt-get install -y --no-install-recommends ffmpeg libgl1 libglib2.0-0 tzdata curl \
    && rm -rf /var/lib/apt/lists/* \
    && groupadd --system --gid 1000 toutpvr \
    && useradd --system --uid 1000 --gid toutpvr --home-dir /data --shell /usr/sbin/nologin toutpvr

# Autorité de certification d'entreprise (proxy TLS interne) : déposez vos fichiers .crt dans docker/ca/ avant la construction ;
# ils sont ajoutés au magasin de confiance de l'image et utilisés par pip pendant la construction. Sans fichier : rien ne change.
COPY docker /opt/docker-build
RUN for f in /opt/docker-build/ca/*.crt; do [ -f "$f" ] && cp "$f" /usr/local/share/ca-certificates/; done; \
    if ls /usr/local/share/ca-certificates/*.crt >/dev/null 2>&1; then update-ca-certificates; fi

WORKDIR /app
COPY wheels /app/wheels
COPY requirements.txt LICENSE-COMMERCIAL.md ./
RUN if ls /opt/docker-build/ca/*.crt >/dev/null 2>&1; then export PIP_CERT=/etc/ssl/certs/ca-certificates.crt; fi; \
    pip install --no-index --no-deps --find-links /app/wheels toutpvr \
    && pip install -r requirements.txt \
    && rm -rf /app/wheels /opt/docker-build \
    && mkdir -p /data \
    && chown -R toutpvr:toutpvr /data

COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

USER toutpvr
VOLUME ["/data"]
EXPOSE 8080
HEALTHCHECK --interval=30s --timeout=5s --start-period=20s --retries=3 \
    CMD python -c "import httpx,sys; sys.exit(0 if httpx.get('http://127.0.0.1:8080/health',timeout=3).status_code==200 else 1)"

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["toutpvr", "start"]
