# 🌐 Multi-Region Three-Tier Web Application on AWS

<div align="center">
  <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/93/Amazon_Web_Services_Logo.svg/2560px-Amazon_Web_Services_Logo.svg.png" height="80" alt="AWS Logo"/>
</div>

> A high-availability, multi-region three-tier application deployed to AWS using **Terraform**, **CloudFormation**, **EKS**, **CodePipeline**, and more.

---

## 📁 Project Architecture

![Architecture Diagram](images/architecture-diagram.png)

> _This diagram shows the high-level deployment across `us-east-1` and `us-west-1` using AWS infrastructure._

---

## 🔧 Technology Stack

| Layer         | Technology                       |
|---------------|----------------------------------|
| Web Tier      | PHP                              |
| App Tier      | Flask (Python)                   |
| DB Tier       | Amazon RDS                       |
| CI/CD         | CodePipeline, CodeBuild          |
| Container     | Docker, Amazon ECR               |
| Orchestration | Amazon EKS                       |
| Security      | SonarQube, Trivy                 |
| IaC           | Terraform (`us-east-1`), CloudFormation (`us-west-1`) |
| Monitoring    | CloudWatch (`us-west-1` cluster) |
| Routing       | Route53 with Failover Policy     |

---

## 🗂️ Multi-Region Deployment Overview

| Region       | IaC Tool       | Pipeline Type      | EKS Deployment      | Load Balancer | Monitoring  |
|--------------|----------------|--------------------|---------------------|----------------|-------------|
| `us-east-1`  | Terraform       | Manual             | Manual Pipeline      | ALB (Auto)     | ❌          |
| `us-west-1`  | CloudFormation | Automated          | CFN-Generated Pipeline | ALB (Auto)     | ✅ CloudWatch |

---

## 🚀 Workflow Overview

### us-east-1 (`Terraform + Manual Pipeline`)
- 🔨 Infra: Built using Terraform
- 🐳 ECR: Images built with Docker
- 🚀 Deployment: Manual pipeline to deploy to EKS
- 🌐 Web/App tiers exposed through ALB

### us-west-1 (`CloudFormation + Auto Pipeline`)
- 🏗️ Infra: CloudFormation templates
- 📦 CI/CD: Auto-provisioned pipeline via CFN
- 🔁 EKS deployment is automatic
- 🔍 Monitoring: CloudWatch metrics (e.g., CPU usage)

---

## ⚙️ Infrastructure Components

| Component        | Description                              |
|------------------|------------------------------------------|
| `VPC`            | Custom VPC with public & private subnets |
| `NAT Gateway`    | Outbound internet access for private subnets |
| `Internet Gateway`| Connects VPC to the internet             |
| `Amazon RDS`     | Database backend                         |
| `Amazon EKS`     | Kubernetes workload manager              |
| `Amazon ECR`     | Stores container images                  |
| `Route53`        | DNS failover between regions             |
| `CloudWatch`     | Monitors CPU usage in `us-west-1`        |

---

## 🔁 CI/CD Pipeline Architecture

![CI/CD Diagram](images/cicd-pipeline.png)

> The CI/CD pipeline includes static analysis and vulnerability scanning before pushing images to ECR and deploying them to EKS.

### 🔍 Tools Used:
- ✅ **CodePipeline**: Orchestrates the CI/CD flow
- ✅ **CodeBuild**: Builds, tests, and scans Docker images
- ✅ **SonarQube**: Static code analysis
- ✅ **Trivy**: Container image vulnerability scanning
- ✅ **CloudFormation / Terraform**: Infrastructure provisioning

---

## 🌐 High Availability with Route 53

![Failover Routing](images/route53-failover.png)

> Route 53 routes traffic to the healthy region automatically using **Failover Routing Policy** between the two ALB endpoints.

---

## 📊 Monitoring and Logging

- Enabled **Amazon CloudWatch** monitoring for:
  - ✅ EKS node CPU utilization in `us-west-1`
  - 📈 Metrics and alerts for critical events

---

## 📁 Suggested Repository Structure

