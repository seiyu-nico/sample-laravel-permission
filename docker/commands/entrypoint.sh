#!/usr/bin/env bash

usermod -u "${UID:-1000}" root
groupmod -g "${GID:-1000}" root

/usr/local/bin/docker-entrypoint.sh unitd --no-daemon --control unix:/var/run/control.unit.sock
