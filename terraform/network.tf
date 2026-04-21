# 1. Virtual Cloud Network (VCN) එක සෑදීම
resource "oci_core_vcn" "autocare_vcn" {
  cidr_block     = "10.0.0.0/16"
  compartment_id = var.compartment_id
  display_name   = "AutoCareVCN"
  dns_label      = "autocarevcn"
}

# 2. Internet Gateway එක සෑදීම (සයිට් එකට පිටතින් එන්න ඉඩ දෙන්න)
resource "oci_core_internet_gateway" "autocare_ig" {
  compartment_id = var.compartment_id
  display_name   = "AutoCareIG"
  vcn_id         = oci_core_vcn.autocare_vcn.id
}

# 3. Route Table එක සැකසීම (ට්‍රැෆික් එක Gateway එකට යොමු කරන්න)
resource "oci_core_route_table" "autocare_rt" {
  compartment_id = var.compartment_id
  vcn_id         = oci_core_vcn.autocare_vcn.id
  display_name   = "AutoCareRouteTable"

  route_rules {
    destination       = "0.0.0.0/0"
    destination_type  = "CIDR_BLOCK"
    network_entity_id = oci_core_internet_gateway.autocare_ig.id
  }
}

# 4. Subnet එක සෑදීම (අපේ සර්වර් එක ඉන්නේ මෙතනයි)
resource "oci_core_subnet" "autocare_subnet" {
  cidr_block        = "10.0.1.0/24"
  display_name      = "AutoCareSubnet"
  compartment_id    = var.compartment_id
  vcn_id            = oci_core_vcn.autocare_vcn.id
  route_table_id    = oci_core_route_table.autocare_rt.id
  dns_label         = "autocaresubnet"
}