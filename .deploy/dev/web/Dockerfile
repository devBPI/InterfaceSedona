FROM httpd:2.4

RUN set -xe ;\
    sed -ri \
		-e 's!^#(.+modules/mod_proxy.so)$!\1!g' \
		-e 's!^#(.+modules/mod_proxy_http.so)$!\1!g' \
		-e 's!^#(.+modules/mod_proxy_fcgi.so)$!\1!g' \
		-e 's!^#(.+modules/mod_expires.so)$!\1!g' \
		-e 's!^#(.+modules/mod_info.so)$!\1!g' \
		-e 's!^#(.+modules/mod_rewrite.so)$!\1!g' \
		"$HTTPD_PREFIX/conf/httpd.conf" ;\
    echo "IncludeOptional conf.d/*.conf" >> "$HTTPD_PREFIX/conf/httpd.conf"

RUN set -xe ;\
    apt-get update ;\
    apt-get install -y dnsutils
