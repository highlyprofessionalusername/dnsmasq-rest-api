dnsmasq-rest-api
================

Dead simple REST Api for controlling (dnsmasq)[http://www.thekelleys.org.uk/dnsmasq/doc.html] server.

API
---

For each zone, dnsmasq-rest-api will write a file (named by the zone name) in the hosts dnsmasq directory.

Each zone can contains multiples lines, like a standard hosts file.

* List zones

```
$ curl http://localhost/dnsmasq-rest-api/zones
["myZone"]
```

* Delete zone

```
$ curl -X DELETE http://localhost/dnsmasq-rest-api/zones/myZone
OK Zone deleted
```

* There is no create zone command. Just add a record into a new zone

```
$ curl http://localhost/dnsmasq-rest-api/zones/myZone/records/127.0.0.1/localhost
OK Record added
```

* List zone records

```
$ curl http://localhost/dnsmasq-rest-api/zones/myZone
{"127.0.0.1":["localhost"]}
```

* Multiple host for same IP is supported

```
$ curl http://localhost/dnsmasq-rest-api/zones/myZone/records/127.0.0.1/localhost2
OK Record added
$ curl http://localhost/dnsmasq-rest-api/zones/myZone
{"127.0.0.1":["localhost","localhost2"]}
```


