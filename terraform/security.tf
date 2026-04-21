resource "oci_core_security_list" "autocare_sl" {
  compartment_id = var.compartment_id
  vcn_id         = oci_core_vcn.autocare_vcn.id
  display_name   = "AutoCareSecurityList"

  # ඉන්ටර්නෙට් එකෙන් ඇතුළට එන ට්‍රැෆික් (Ingress)
  
  # SSH සඳහා (Port 22)
  ingress_security_rules {
    protocol    = "6" # TCP
    source      = "0.0.0.0/0"
    tcp_options {
      min = 22
      max = 22
    }
  }

  # AutoCare App එක සඳහා (Port 8080)
  ingress_security_rules {
    protocol    = "6" # TCP
    source      = "0.0.0.0/0"
    tcp_options {
      min = 8080
      max = 8080
    }
  }

  # සර්වර් එකේ සිට පිටතට යන ට්‍රැෆික් (Egress - Allow All)
  egress_security_rules {
    protocol    = "all"
    destination = "0.0.0.0/0"
  }
}