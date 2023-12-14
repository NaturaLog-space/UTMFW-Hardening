Hello,

I believe the engineering team would appreciate this.

*FIREWALL IMAGE AND VERSION OpenBSD 7.4 / UTMFW 7.3 + Mods

I am a student/researcher who has to create a firewall to defend confidential research. I would like to share a method of reducing attack surface and infection that would make the internet safer.

In my system, I currently have the system start as a ramdisk. The system loads all antiviruses, snort, ssl, vpn etc. After every software is loaded into memory, the basic files used in terminal are deleted from ramdisk.

 ( /bin /sbin /usr/bin : ssh ftp httpd wget compilers vim nano etc ) 

After everything is loaded and cleaned, eth0 eth1 wan/lan is activated.

If there is an infection the system can be restarted fresh on the ramdisk.


(Optional):
I run a gui on my system so I dont have HTTPD running as a vulnerability point for a control panel. SSH and HTTPD seems to be common entry points for hackers. Localizing the control panel to the system would greatly decrease attack surface.


Attacks on memory become the focus after a hacker sees that basic commands no longer exist on the system. 
Detecting memory leaks or overflow would be the main focus of the internal malware detection. A simple restart would prevent further exploitation of the equipment. A more advanced technique would be continually refreshing memory with clean snapshots of memory. 


Best regards,
Douglas


Forked and being hardened
https://github.com/NaturaLog-space/UTMFW-Hardening/tree/master
