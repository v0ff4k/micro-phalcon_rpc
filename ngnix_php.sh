#!/usr/bin/env bash
##or #!/bin/bash

service nginx start
service php7.1-fpm start

# make a loop
tail -F -n0 /etc/hosts