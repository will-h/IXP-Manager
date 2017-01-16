<?php
/*
 * Bird Route Server Configuration Template
 *
 *
 * You should not need to edit these files - instead use your own custom skins. If
 * you can't effect the changes you need with skinning, consider posting to the mailing
 * list to see if it can be achieved / incorporated.
 *
 * Skinning: https://ixp-manager.readthedocs.io/en/latest/features/skinning.html
 *
 * Copyright (C) 2009-2016 Internet Neutral Exchange Association Company Limited By Guarantee.
 * All Rights Reserved.
 *
 * This file is part of IXP Manager.
 *
 * IXP Manager is free software: you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation, version v2.0 of the License.
 *
 * IXP Manager is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for
 * more details.
 *
 * You should have received a copy of the GNU General Public License v2.0
 * along with IXP Manager.  If not, see:
 *
 * http://www.gnu.org/licenses/gpl-2.0.html
 */
?>
#
# Bird Route Server configuration generated by IXP Manager
#
# Do not edit this file, it will be overwritten. Please see:
#
# https://github.com/inex/IXP-Manager/wiki/Route-Server
#
# Generated: <?= date('Y-m-d H:i:s') . "\n" ?>
#

# For VLAN: {$t->vlan->getName()} (Tag: {$t->vlan->getNumber()}, Database ID: {$t->vlan->getId()})

log "/var/log/bird/<?= $t->handle ?>.log" all;
log syslog all;

define routeserverasn     = <?= $t->router->asn() ?>;
define routeserveraddress = <?= $t->router->peeringIp() ?>;

router id <?= $t->router->routerId() ?>;

listen bgp address routeserveraddress;

# ignore interface up/down events
protocol device { }

# This function excludes weird networks
#  rfc1918, class D, class E, too long and too short prefixes
function avoid_martians()
prefix set martians;
{
    <?php if( $t->router->protocol() == 6 ): ?>

        martians = [
                ::/0,                   # Default (can be advertised as a route in BGP to peers if desired)
                ::/96,                  # IPv4-compatible IPv6 address - deprecated by RFC4291
                ::/128,                 # Unspecified address
                ::1/128,                # Local host loopback address
                ::ffff:0.0.0.0/96+,     # IPv4-mapped addresses
                ::224.0.0.0/100+,       # Compatible address (IPv4 format)
                ::127.0.0.0/104+,       # Compatible address (IPv4 format)
                ::0.0.0.0/104+,         # Compatible address (IPv4 format)
                ::255.0.0.0/104+,       # Compatible address (IPv4 format)
                0000::/8+,              # Pool used for unspecified, loopback and embedded IPv4 addresses
                0200::/7+,              # OSI NSAP-mapped prefix set (RFC4548) - deprecated by RFC4048
                3ffe::/16+,             # Former 6bone, now decommissioned
                2001:db8::/32+,         # Reserved by IANA for special purposes and documentation
                2002:e000::/20+,        # Invalid 6to4 packets (IPv4 multicast)
                2002:7f00::/24+,        # Invalid 6to4 packets (IPv4 loopback)
                2002:0000::/24+,        # Invalid 6to4 packets (IPv4 default)
                2002:ff00::/24+,        # Invalid 6to4 packets
                2002:0a00::/24+,        # Invalid 6to4 packets (IPv4 private 10.0.0.0/8 network)
                2002:ac10::/28+,        # Invalid 6to4 packets (IPv4 private 172.16.0.0/12 network)
                2002:c0a8::/32+,        # Invalid 6to4 packets (IPv4 private 192.168.0.0/16 network)
                fc00::/7+,              # Unicast Unique Local Addresses (ULA) - RFC 4193
                fe80::/10+,             # Link-local Unicast
                fec0::/10+,             # Site-local Unicast - deprecated by RFC 3879 (replaced by ULA)
                ff00::/8+               # Multicast
        ];

    <?php else: ?>

        martians = [
                10.0.0.0/8+,
                169.254.0.0/16+,
                172.16.0.0/12+,
                192.0.0.0/24+,
                192.0.2.0/24+,
                192.168.0.0/16+,
                198.18.0.0/15+,
                198.51.100.0/24+,
                203.0.113.0/24+,
                224.0.0.0/4+,
                240.0.0.0/4+,
                0.0.0.0/32-,
                0.0.0.0/0{25,32},
                0.0.0.0/0{0,7}
        ];

    <?php endif; ?>

        # Avoid RFC1918 and similar networks
        if net ~ martians then
                return false;

        return true;
}
