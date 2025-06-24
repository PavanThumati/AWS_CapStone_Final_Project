# 🌐 Multi-Region Three-Tier Application Deployment on AWS

This project demonstrates a highly available, fault-tolerant **Three-Tier Architecture** deployed across two AWS regions (`us-east-1` and `us-west-1`). It uses a combination of **Terraform**, **CloudFormation**, and various AWS DevOps services for CI/CD, container orchestration, and infrastructure automation.

---

## 🧱 Architecture Overview

**Tier Breakdown:**
- **Database Tier:** Amazon RDS (in both regions)
- **Application Tier:** Python Flask app (deployed in EKS as pods)
- **Web Tier:** PHP-based frontend

**Regions Involved:**
- **us-east-1:** Infrastructure created using **Terraform**
- **us-west-1:** Infrastructure created using **CloudFormation**

---

## 🚀 Deployment Workflow

### `us-east-1` Region:
- Infrastructure provisioned using **Terraform**
- **EKS** cluster created manually
- CI/CD pipeline **manually created** using:
  - **AWS CodePipeline**
  - **AWS CodeBuild**
- Application container images pulled from **Amazon ECR**
- Pods deployed to EKS cluster

### `us-west-1` Region:
- Infrastructure provisioned using **CloudFormation**
- CI/CD pipeline **automatically created** via CloudFormation
- Pipeline handles:
  - Code checkout
  - Build (CodeBuild)
  - Security scanning with **Trivy**
  - Quality analysis with **SonarQube**
  - Deployment to **EKS**

---

## 🌍 Load Balancing & DNS Routing

- Load Balancers created in both regions after application deployment
- **Amazon Route 53** used with **Failover Routing Policy** to route traffic across regions
- Ensures high availability and automatic region failover

---

## 📊 Monitoring & Observability

- **Amazon CloudWatch** enabled for `us-west-1` EKS cluster
- Monitors **CPU utilization** of nodes

---

## 🛠️ Tools & Services Used

### 🧰 DevOps & CI/CD
- **AWS CodePipeline**
- **AWS CodeBuild**
- **Amazon ECR**
- **SonarQube**
- **Trivy**

### ☁️ Infrastructure
- **Terraform** (for `us-east-1`)
- **CloudFormation** (for `us-west-1`)
- **Amazon VPC, Subnets, NAT Gateway, Internet Gateway**
- **IAM roles:** EKS Node Role, EKS Role, Pipeline Role, etc.

---

## 🧱 Infrastructure Components

- VPC with public and private subnets
- NAT Gateway, Internet Gateway
- ECR Repositories for app and web images
- EKS clusters in both regions
- RDS instances for database
- IAM roles for EKS, pipeline, and node groups

---

## 📦 Project Structure

