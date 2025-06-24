# Multi-Region AWS Infrastructure for CI/CD-Driven Application with Disaster Recovery

![Architecture Diagram](docs/architecture.png)

## Table of Contents
- [Overview](#overview)  
- [Architecture Diagram](#architecture-diagram)  
- [Core Components](#core-components)  
  - [Application Layer](#application-layer)  
  - [Region A (Primary – us-east-1)](#region-a-primary––us-east-1)  
  - [Region B (Disaster Recovery – us-west-2)](#region-b-disaster-recovery––us-west-2)  
  - [Global Services](#global-services)  
  - [Additional Features](#additional-features)  
- [Prerequisites](#prerequisites)  
- [Getting Started](#getting-started)  
  - [1. Clone the Repo](#1-clone-the-repo)  
  - [2. Deploy Region A (CloudFormation)](#2-deploy-region-a-cloudformation)  
  - [3. Deploy Region B (Terraform)](#3-deploy-region-b-terraform)  
  - [4. Verify Route 53 Failover](#4-verify-route-53-failover)  
- [Directory Structure](#directory-structure)  
- [Contributing](#contributing)  
- [License](#license)  

---

## Overview
This repository contains all the artifacts, Infrastructure-as-Code (IaC) stacks, Kubernetes manifests, and the finalized AWS architecture diagram for a **multi-region**, **CI/CD-driven** web application with built-in **disaster recovery**. The application front-end is written in PHP, the back-end uses Flask/Python, and the database is Amazon RDS (MySQL) configured in Multi-AZ.

---

## Architecture Diagram
![Multi-Region AWS CI/CD Diagram](docs/architecture.png)  
> **Highlights**  
> - Two regions (Primary – us-east-1, DR – us-west-2) with separate VPCs, subnets, NAT/Internet Gateways  
> - EKS clusters hosting PHP front-end & Flask back-end  
> - RDS MySQL Multi-AZ (primary) + DR replica/snapshot plan  
> - CI/CD pipelines: Region A via CloudFormation & CodePipeline; Region B via Terraform & mirrored pipelines  
> - Global Route 53 failover routing with health checks  
> - Monitoring via CloudWatch, alerts via SNS  

---

## Core Components

### Application Layer
- **Frontend**: PHP application deployed on Amazon EKS.  
- **Backend**: Flask (Python) services on the same EKS cluster.  
- **Database**: Amazon RDS MySQL in Multi-AZ for high availability.

### Region A (Primary – us-east-1)
**Networking**  
- VPC with 2× public & 2× private subnets across AZ-a & AZ-b  
- Internet Gateway (IGW) in public subnets  
- NAT Gateway in each AZ for outbound internet from private subnets  

**Compute & Storage**  
- Amazon EKS cluster  
  - 2+ Node Groups (private subnets)  
  - Service-linked IAM roles for EKS  
- Amazon RDS MySQL Multi-AZ  

**Container Registry**  
- Amazon ECR (private) with image scanning via Trivy  

**CI/CD (CodePipeline)**  
1. **Source**: GitHub (CodeStar Connection)  
2. **Build**: CodeBuild  
   - SonarQube for static analysis  
   - Trivy for container vulnerability scanning  
3. **Deploy**: `kubectl apply` of Kubernetes manifests to EKS  

**Monitoring & Alerts**  
- CloudWatch Metrics & Logs  
- Log Groups for EKS & CodeBuild  
- Alarm: EKS Node CPU > 75% → SNS notification  

### Region B (Disaster Recovery – us-west-2)
- Mirror VPC, subnets, EKS, RDS (via read-replica or periodic snapshot restore)  
- **IaC**: Terraform modules for networking, EKS, RDS, CodePipeline  
- CodeBuild & CodePipeline set up analogous to Region A for DR testing  

### Global Services
- **Route 53 Failover Routing**  
  - Health checks against Region A & B ALB/NLB endpoints  
  - Automated DNS switch to DR if primary fails  

### Additional Features
- IAM roles & service-linked roles scoped per service  
- Private ECR image scanning (Trivy integration)  
- Static code analysis (SonarQube integration)  
- CloudWatch Log Groups retention & encryption  

---

## Prerequisites
- AWS CLI v2 configured with `us-east-1` & `us-west-2` profiles  
- `kubectl`, `eksctl`, `terraform`, `aws-sam-cli` installed  
- GitHub repo & CodeStar connection set up  
- SonarQube server endpoint & credentials  
- Trivy CLI or integration in CodeBuild images  

---

## Getting Started

### 1. Clone the Repo
```bash
git clone https://github.com/<your-org>/aws-multi-region-ci-cd-arch.git
cd aws-multi-region-ci-cd-arch
