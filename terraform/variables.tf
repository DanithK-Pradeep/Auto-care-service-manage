variable "tenancy_ocid" {}
variable "user_ocid" {}
variable "fingerprint" {}
variable "private_key_path" {}
variable "region" {}

# ඔයාගේ Dashboard එකේ Identity -> Compartments වලින් මෙය ලබාගන්න
variable "compartment_id" {
  default = "ocid1.tenancy.oc1..aaaaaaaaxzmrykpvvlwosjd6odl5isfshe5mevdafhjcemrurw7mmapym5ua"
}

