variable "project_name" {
  default = "marketreminder-206206"
}

variable "project_zone" {
  default = "europe-west3"
}

provider "google" {
credentials = "${file("marketReminder_google_credentials.json")}"
  project = "${var.project_name}"
  zone = "${var.project_zone}"
}

resource "google_sourcerepo_repository" "repository" {
  name = "marketReminder_repository"
}

resource "google_container_cluster" "project_cluster" {
  name = "marketreminder"
  zone = "${var.project_zone}"
  node_pool {
    initial_node_count = 3
    autoscaling {
      min_node_count = 3
      max_node_count = 6
    }
    management {
      auto_repair = true
    }
    node_config {
      machine_type = "f1-micro"
    }
  }
}

resource "google_cloudbuild_trigger" "master_trigger" {
  project = "${var.project_name}"
  trigger_template {
    branch_name = "master"
    project = "${var.project_name}"
    repo_name = "marketReminder_repository"
  }
  filename = "cloudbuild.json"
}

resource "google_cloudbuild_trigger" "develop_trigger" {
  project = "${var.project_name}"
  trigger_template {
    branch_name = "develop"
    project = "${var.project_name}"
    repo_name = "marketReminder_repository"
  }
  filename = "cloudbuild.json"
}
