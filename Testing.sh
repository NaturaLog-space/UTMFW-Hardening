#once you have setup the firewall before going online run this script
# simple test to remove all commands to increase intrusion difficulty

cd /bin
doas rm -r -f ksh sh csh tcsh ssh chmod
cd /usr/bin
doas rm -r -f ftp sshd ssh* 

#or 
#doas rm -r -f /*
