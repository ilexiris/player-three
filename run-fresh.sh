#!/bin/bash
set -xeuo pipefail

TAG='player-three'
NAME='player-three'

podman stop "$NAME" || true
podman rm "$NAME" || true
podman build --tag "$TAG" .
podman run \
	-p 8080:80 \
	--name "$NAME" \
	-v "$PWD/public":/var/www/html \
	-v "$PWD/src":/var/www/src \
	"$TAG"
