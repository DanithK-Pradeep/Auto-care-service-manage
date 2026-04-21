resource "oci_core_instance" "autocare_instance" {
  availability_domain = data.oci_identity_availability_domains.ads.availability_domains[0].name
  compartment_id      = var.compartment_id
  shape               = "VM.Standard.E2.1.Micro" # ඔයාට අවශ්‍ය shape එක මෙතනට දෙන්න

  display_name = "AutoCareServer"

  create_vnic_details {
    subnet_id        = oci_core_subnet.autocare_subnet.id
    display_name     = "primaryvnic"
    assign_public_ip = true
  }

  source_details {
    source_type = "image"
    source_id   = "ocid1.image.oc1.ap-mumbai-1.aaaaaaaa3yc7aswdetryjk6knfe5zlex6opdvab5oazebaebst5zri3ocxcq" 
  }

  metadata = {
    ssh_authorized_keys = file("C:/Users/User/.ssh/id_rsa.pub")
  }
}

# Availability Domains ලබා ගැනීමට
data "oci_identity_availability_domains" "ads" {
  compartment_id = var.compartment_id
}