#!/bin/bash

#set -x

PROG=$(basename $0)

usage() {
	echo "usage: $PROG"
}

main() {
	# install the Public Cloud WF and MS
	cd /opt/fmc_repository
	git clone https://github.com/ubiqube/IAP.git github_ubiqube_iap_public_cloud
	### WF
	cd /opt/fmc_repository/Process/
	ln -fs ../github_iap_public_cloud/Workflows/Public_Cloud Public_Cloud
	ln -fs ../github_iap_public_cloud/Workflows/.meta_Public_Cloud .meta_Public_Cloud
	chown -R ncuser.ncuser /opt/fmc_repository/* /opt/fmc_repository/.* 
    ### MS
	cd /opt/fmc_repository/CommandDefinition/	
	ln -fs ../github_iap_public_cloud/Microservices/IAP IAP
	ln -fs ../github_iap_public_cloud/Microservices/.meta_IAP .meta_IAP

    #install private cloud WF and MS
	cd /opt/fmc_repository/Process/
	git clone https://github.com/ubiqube/IAP.git github_ubiqube_aip_private_cloud
	ln -fs ../github_iap_private_cloud/Workflows/Private_Cloud Private_Cloud
	ln -fs ../github_iap_private_cloud/Workflows/.meta_Private_Cloud .meta_Private_Cloud


	# install DA
	cd /opt/sms/bin/php ; 
    git clone https://github.com/openmsa/Adaptors.git github_openmsa_adapters; 
    chown -R ncuser:ncuser github_openmsa_adapters; 
    ln -s OpenMSA_Adapters/adapters/rest_generic rest_generic; 
    chown -R ncuser:ncuser rest_generic; 
    cd /opt/sms/templates/devices/; 
    mkdir -p /opt/sms/templates/devices/rest_generic/conf; 
    cd /opt/sms/templates/devices/rest_generic/conf; 
    ln -s /opt/sms/bin/php/rest_generic/conf/sms_router.conf sms_router.conf; 
    chown -R ncuser:ncuser /opt/sms/templates/devices/; 
    cd /opt/ubi-jentreprise/resources/templates/conf/device/; 
    mkdir custom; 
    cp manufacturers.properties custom; 
    cp models.properties custom; 
    cd /opt/sms/bin/php/github_openmsa_adapters/bin/; 
    ./da_installer install /opt/sms/bin/php/rest_generic; 
    /opt/ubi-jentreprise/configure; 


}

main "$@"