# AWS Capstone Final Project: Multi-Region CI/CD-Driven Application with Disaster Recovery

This repository hosts the infrastructure as code, application code, and documentation for a robust, multi-tier application deployed across multiple AWS regions with a focus on automated CI/CD and comprehensive disaster recovery capabilities.

## 🌟 Project Overview

This project implements a highly available and resilient multi-tier web application featuring a PHP frontend and a Flask/Python backend, leveraging Amazon Elastic Kubernetes Service (EKS) for container orchestration, Amazon RDS for database services, and a sophisticated CI/CD pipeline for automated deployments. A key aspect is the multi-region active-passive disaster recovery strategy, ensuring business continuity with minimal downtime.

## 🚀 Architecture Diagram (Conceptual)

While a visual diagram is best viewed in a dedicated tool, this section describes the high-level layout. Imagine two distinct AWS regions, `us-east-1` (Primary) and `us-west-2` (Disaster Recovery), connected globally via Route 53.

```
53.

+---------------------------------------------------------------------------------------------------------------------------------------------------------------------+
|                                                                    GLOBAL SERVICES                                                                                |
|                                                                                                                                                                     |
|  +---------------------+                                                                                                                                            |
|  |     Amazon Route 53   |                                                                                                                                            |
|  | (Failover Routing)  |--------------------------------------------------+----------------------------------------------------------------------------------+       |
|  +---------------------+                                                  | (Routes to ALB)                                                                  | (Routes to ALB)       |
|                                                                           |                                                                                  |       |
+---------------------------------------------------------------------------|----------------------------------------------------------------------------------|-------+
                                                                            |                                                                                  |
                                                                            |                                                                                  |
+---------------------------------------------------------------------------|---------------------------------+--------------------------------------------------|-------+
|                                  REGION A (PRIMARY - us-east-1)           |                                 |   REGION B (DISASTER RECOVERY - us-west-2)     |
|                                                                           |                                 |                                                  |
|  +---------------------------+       +-------------------------------+    |    +---------------------------+       +------------------------------------+      |
|  |       Networking          |       |        CI/CD Pipeline         |    |    |       Networking          |       |      CI/CD Pipeline (Mirrored)     |      |
|  | - VPC                     |       | - GitHub (Source)             |    |    | - Duplicate VPC           |       | - GitHub (Source - mirrored)       |      |
|  | - Public/Private Subnets  |       | - CodeStar Connection         |    |    | - Public/Private Subnets  |       | - CodeStar Connection              |      |
|  | - Internet Gateway (IGW)  |       | - CodePipeline                |    |    | - Internet Gateway (IGW)  |       | - CodePipeline                     |      |
|  | - NAT Gateway             |       |   - Build (CodeBuild +        |    |    | - NAT Gateway             |       |   - Build (CodeBuild +             |      |
|  +---------------------------+       |     SonarQube, Trivy)         |    |    +---------------------------+       |     SonarQube, Trivy)              |      |
|            |                         |   - ECR Push (Trivy Scan)     |    |              |                         |   - ECR Push (Trivy Scan)          |      |
|            |                         |   - Deploy (EKS, K8s Manifests)|    |              |                         |   - Deploy (EKS, K8s Manifests)    |      |
|            |                         +-------------------------------+    |              |                         +------------------------------------+      |
|            |                                    |                         |              |                                    |                                |
|            |                                    |                         |              |                                    |                                |
|  +---------------------------+       +-------------------------------+    |    +---------------------------+       +-------------------------------+         |
|  |     Application Tier      |-------|      Container Registry       |    |    |     Application Tier      |-------|      Container Registry       |         |
|  | - Amazon EKS Cluster      |-------| - Amazon ECR                  |    |    | - Duplicate Amazon EKS    |-------| - Amazon ECR                  |         |
|  |   - 2+ Node Groups        |       |   (Private Image Scanning)    |    |    |   Cluster                 |       |   (Private Image Scanning)    |         |
|  |   - PHP Frontend          |       +-------------------------------+    |    |   - 2+ Node Groups        |       +-------------------------------+         |
|  |   - Flask/Python Backend  |                                           |    |   - PHP Frontend (DR)     |                                                  |
|  |   - (ALB/NLB for web tier)|                                           |    |   - Flask/Python Backend (DR)|                                                  |
|  +---------------------------+                                           |    |   - (ALB/NLB for web tier)|                                                  |
|            |                                                             |    +---------------------------+                                                  |
|            |                                                             |              |                                                                     |
|  +---------------------------+                                           |    +---------------------------+                                             |
|  |      Database Tier        |                                           |    |     Database Tier         |                                             |
|  | - Amazon RDS (MySQL)      |                                           |    | - Amazon RDS (MySQL)      |                                             |
|  |   - Multi-AZ Deployment   |                                           |    |   (DR/Replica/Snapshot)   |                                             |
|  +---------------------------+                                           |    +---------------------------+                                             |
|                                                                           |                                                                                  |
|  +---------------------------+                                            |                                                                                  |
|  |   Monitoring & Alerting   |                                            |                                                                                  |
|  | - Amazon CloudWatch       |                                            |                                                                                  |
|  |   - Log Groups            |                                            |                                                                                  |
|  |   - Alarms (EKS CPU > 75%)|                                            |                                                                                  |
|  | - Amazon SNS (Alerts)     |                                            |                                                                                  |
|  +---------------------------+                                            |                                                                                  |
+---------------------------------------------------------------------------------------------------------------------------------------------------------------------+

````

## ✨ Features

* **Multi-Tier Application:**
    * **Frontend:** PHP application served via EKS.
    * **Backend:** Flask and Python application, deployed on EKS.
    * **Database:** Amazon RDS for MySQL with Multi-AZ deployment for high availability.
* **Automated CI/CD:**
    * Source code management with GitHub.
    * Automated build, test, and deployment using AWS CodePipeline and CodeBuild.
    * Container image management with Amazon ECR.
* **Code Quality & Security:**
    * Static code analysis with **SonarQube** integration in the build process.
    * Container image vulnerability scanning with **Trivy** pre-push to ECR.
* **Multi-Region Disaster Recovery (DR):**
    * Active-passive DR strategy utilizing `us-east-1` as primary and `us-west-2` as DR region.
    * Automated failover routing via Amazon Route 53 health checks.
    * Duplicate infrastructure in the DR region, provisioned via Terraform for consistency.
* **Robust Monitoring & Alerting:**
    * Centralized logging and metrics with Amazon CloudWatch.
    * Real-time alerts via Amazon SNS (e.g., EKS Node CPU utilization exceeding 75%).
* **Security Best Practices:**
    * Granular IAM roles and service-linked roles for AWS services.
    * VPC private subnets for application and database tiers.

## 🔧 AWS Services Used

* **Networking:**
    * Amazon VPC, Public/Private Subnets
    * Internet Gateway (IGW)
    * NAT Gateway
* **Compute & Containerization:**
    * Amazon EKS (Elastic Kubernetes Service)
    * Amazon EC2 (for EKS Worker Nodes)
    * Amazon Elastic Container Registry (ECR)
* **Database:**
    * Amazon RDS for MySQL (Multi-AZ)
* **Developer Tools (CI/CD):**
    * AWS CodePipeline
    * AWS CodeBuild
    * AWS CodeStar Connections (for GitHub integration)
* **Management & Governance:**
    * Amazon CloudWatch (Logs, Metrics, Alarms)
    * Amazon SNS (Simple Notification Service)
    * AWS IAM (Identity and Access Management)
    * Amazon Route 53
* **Infrastructure as Code:**
    * Terraform (for Region B DR deployment)

## 🔄 CI/CD Pipeline Details

The CI/CD pipeline automates the journey of your application code from GitHub to a running service on EKS.

1.  **Source Stage (GitHub):**
    * Code changes pushed to the `main` branch in GitHub automatically trigger CodePipeline via a CodeStar Connection.
2.  **Build Stage (CodeBuild):**
    * CodeBuild environment is provisioned.
    * **Static Code Analysis:** SonarQube scanner runs against the PHP and Python codebases.
    * **Dependency Management & Build:** Frontend (PHP) and Backend (Flask/Python) applications are built, and their respective Docker images are created.
    * **Image Scanning:** Trivy scans the newly built Docker images for vulnerabilities. If critical vulnerabilities are found, the build can be configured to fail.
    * Build artifacts, including container images and Kubernetes manifests, are prepared.
3.  **Push to ECR Stage:**
    * The validated container images (PHP frontend, Flask backend) are pushed to their respective repositories in Amazon ECR.
    * ECR's private image scanning feature provides an additional layer of security post-push.
4.  **Deploy Stage (EKS):**
    * CodeBuild or a direct CodePipeline action applies the Kubernetes manifests (e.g., `Deployment`, `Service`, `Ingress`) to the Amazon EKS cluster in `us-east-1`. This updates the application to the latest version.

               
                     
               ┌────────────┐       ┌────────────┐       ┌──────────────┐       ┌────────────┐
               │  GitHub    │ ───▶ │ CodeBuild  │ ───▶  │     ECR      │ ───▶ │EKS/Kubernetes│
               └────────────┘       └────────────┘       └──────────────┘       └────────────┘
                    ▲                        │                   │                       │
                    └─────────────[Triggered on push]────────────┴────[Rolling updates / hooks]
               

## 🌐 Disaster Recovery Strategy

The DR strategy ensures the application remains available even in the event of a regional outage.

* **Active-Passive Setup:** `us-east-1` is the primary active region handling all traffic. `us-west-2` is the passive, standby DR region.
* **Data Replication:** Amazon RDS in `us-east-1` replicates data to `us-west-2` via cross-region read replicas (which can be promoted) or consistent snapshot replication, ensuring low Recovery Point Objective (RPO).
* **Infrastructure as Code for DR:** The infrastructure in `us-west-2` is managed by Terraform, allowing for rapid and consistent provisioning of an identical environment to the primary.
* **Automated Failover (Route 53):**
    * Route 53 performs continuous health checks on the application's endpoint (e.g., ALB/NLB) in both `us-east-1` and `us-west-2`.
    * If the primary region's endpoint fails its health checks, Route 53 automatically updates DNS records to direct all new user traffic to the `us-west-2` region.
    * Upon failover, the RDS replica in `us-west-2` is promoted to primary, and EKS services are scaled up/activated to handle incoming traffic.

## ⚙️ Setup and Deployment (High-Level)

1.  **Prerequisites:**
    * AWS Account with administrative access.
    * AWS CLI configured.
    * Terraform installed (for DR setup).
    * `kubectl` configured for EKS interaction.
    * Docker installed.
2.  **Clone Repository:**
    ```bash
    git clone [https://github.com/PavanThumati/AWS_CapStone_Final_Project.git](https://github.com/PavanThumati/AWS_CapStone_Final_Project.git)
    cd AWS_CapStone_Final_Project
    ```
3.  **Region A (Primary) Deployment:**
    * Define VPC, subnets, IGW, NAT Gateway using AWS CLI or CloudFormation/Terraform scripts (if separate IaC is used for primary).
    * Deploy EKS Cluster and Node Groups.
    * Create RDS MySQL Multi-AZ instance.
    * Configure ECR repositories.
    * Set up AWS CodePipeline, CodeBuild projects, and CodeStar Connection to your GitHub repo. This will automatically trigger initial deployments.
    * Ensure IAM roles and security groups are correctly configured.
4.  **Region B (Disaster Recovery) Deployment:**
    * Navigate to the `terraform/dr-region` directory.
    * Initialize and apply Terraform to provision the duplicate VPC, EKS cluster, and standby RDS instance.
    * Configure the mirrored CodePipeline in `us-west-2` (if necessary, for DR drills or specific multi-region deployments).
5.  **Route 53 Configuration:**
    * Configure the Route 53 hosted zone with failover routing policies, pointing to the ALB/NLB endpoints in both regions, and associate health checks.

*(Note: Specific `terraform` directories, `kubectl` commands, and CodePipeline configurations will be detailed in dedicated sub-directories/documentation within this repository.)*

## 📊 Monitoring and Alerting

* **CloudWatch:** Collects and stores logs from EKS pods, application services, CodeBuild, CodePipeline, VPC Flow Logs, and Load Balancer Access Logs. Metrics for EKS nodes, pods, and database performance are also collected.
* **CloudWatch Alarms:** Configured to trigger alerts for critical conditions, such as:
    * EKS Node CPU Utilization > 75%
    * Application error rates, latency spikes
    * Database connection errors, high CPU/memory utilization
* **SNS Notifications:** Alarms publish messages to SNS topics, which then notify operations teams via email, Slack, or other integrated services.

## 🔒 Security Considerations

* **IAM Least Privilege:** All AWS services operate with the minimum necessary IAM permissions.
* **Network Isolation:** Application and database tiers reside in private subnets, not directly accessible from the internet.
* **Security Groups & NACLs:** Granular traffic control at the instance and subnet levels.
* **Container Security:** Trivy scanning during CI/CD and ECR's native scanning ensure container images are free of known vulnerabilities.
* **Code Quality:** SonarQube helps identify and remediate security vulnerabilities within the application code itself.

## 👋 Contributing

Contributions are welcome! Please fork the repository and submit a pull request with your improvements.

````
