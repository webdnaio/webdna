rabbitmqctl add_user webdna webdna
rabbitmqctl add_vhost /webdna
rabbitmqctl set_permissions -p /webdna webdna ".*" ".*" ".*"