# 🏗️ Three-Tier EKS Application Deployment on AWS (via CloudFormation)

This project provisions a full-stack AWS infrastructure using **CloudFormation**, suitable for deploying a containerized application using **Amazon EKS**, **RDS**, **CodePipeline**, and more. It supports automated CI/CD with GitHub integration and CodeBuild.

---

## 📌 Architecture Overview

This architecture includes:
- **Networking:** Custom VPC with public/private subnets, NAT Gateway, and routing tables
- **Compute:** EKS Cluster with node groups
- **Storage:** RDS MySQL for backend database
- **CI/CD:** CodePipeline with GitHub integration, CodeBuild, and deployment to EKS
- **Container Registry:** Amazon ECR
- **Artifact Storage:** Amazon S3

![Architecture Diagram](https://via.placeholder.com/1000x600.png?text=Architecture+Diagram)

---

## 🧱 Infrastructure Components

| **Component**         | **Service**               | **Details**                                                  |
|-----------------------|---------------------------|--------------------------------------------------------------|
| VPC                  | Amazon VPC                | Custom /16 VPC with 2 public and 2 private subnets          |
| Internet Gateway      | Amazon EC2                | Enables internet access for public subnets                  |
| NAT Gateway           | Amazon EC2                | Enables internet access for private subnets                 |
| EKS Cluster           | Amazon EKS                | Kubernetes control plane                                     |
| EKS Node Group        | Amazon EKS                | Worker nodes (t3.medium)                                     |
| RDS Database          | Amazon RDS                | MySQL v8.0.36 in private subnet                              |
| IAM Roles             | AWS IAM                   | For EKS, CodeBuild, CodePipeline                            |
| CI/CD Pipeline        | CodePipeline              | GitHub → CodeBuild → EKS                                     |
| Build Service         | CodeBuild                 | Uses buildspec.yml to build Docker images                   |
| Container Registry    | Amazon ECR                | Stores frontend/backend Docker images                        |
| Artifact Bucket       | Amazon S3                 | Stores pipeline artifacts                                    |

---

## 🔁 Workflow Stages

| **Stage** | **Service**        | **Description**                                                                            |
|-----------|--------------------|--------------------------------------------------------------------------------------------|
| Source    | CodePipeline + GitHub | Connects to GitHub repo using CodeStar connection                                          |
| Build     | CodeBuild           | Builds Docker images for frontend & backend, pushes to ECR                                |
| Deploy    | CodePipeline + EKS  | Applies Kubernetes manifests to the EKS cluster                                            |

![Workflow Diagram](https://via.placeholder.com/800x400.png?text=CI/CD+Pipeline+Workflow)

---

## 🚀 Prerequisites

- An AWS account with IAM privileges to create IAM roles, VPCs, and EKS
- GitHub repository and a [CodeStar Connection](https://docs.aws.amazon.com/dtconsole/latest/userguide/connections-create-github.html)
- `buildspec.yml`, `frontend-deployment.yaml`, `backend-deployment.yaml` in the root of the repository

---

## ⚙️ Parameters (customizable in CloudFormation)

| **Parameter**         | **Default Value**                          | **Description**                                 |
|-----------------------|--------------------------------------------|-------------------------------------------------|
| GitHubRepo            | `PavanThumati/php-flask-application`       | Full repo name for CodeStar connection         |
| GitHubBranch          | `main`                                     | GitHub branch to monitor                       |
| GitHubConnectionArn   | *ARN of GitHub CodeStar Connection*        | Used by CodePipeline for source stage          |
| DBUsername            | `admin`                                    | Master DB username                             |
| DBPassword            | `adminadmin`                               | Master DB password (NoEcho)                    |
| EKSAdminIAMArn        | *IAM user ARN*                             | Grants EKS cluster admin access                |

---

## 🧪 Deployment Instructions

1. **Clone or fork this repository.**
2. **Ensure your `buildspec.yml` and Kubernetes manifests are correct.**
3. **Deploy using AWS Console or CLI:**

   ```bash
   aws cloudformation deploy \
     --template-file fullstack-eks.yml \
     --stack-name three-tier-app-stack \
     --capabilities CAPABILITY_NAMED_IAM
````

4. **Monitor the stack creation.**
5. **View your application URL from the LoadBalancer created by EKS.**

---

## 📁 File Structure

```text
.
├── fullstack-eks.yml              # CloudFormation template
├── buildspec.yml                  # Build instructions for CodeBuild
├── frontend-deployment.yaml      # K8s manifest for frontend
├── backend-deployment.yaml       # K8s manifest for backend
└── README.md                      # Documentation
```

---

## 🛡️ Security Notes

* Sensitive parameters like `DBPassword` are masked using `NoEcho`.
* RDS is not publicly accessible.
* EKS admin access is limited to a provided IAM user.

---

## 👥 Contributors

* [Your Name](https://github.com/yourgithub) – Infrastructure & CI/CD
* [OpenAI's ChatGPT](https://openai.com/chatgpt) – Markdown Formatting & Diagrams

---

## 📎 Resources

* [Amazon EKS](https://docs.aws.amazon.com/eks/latest/userguide/what-is-eks.html)
* [AWS CodePipeline](https://docs.aws.amazon.com/codepipeline/latest/userguide/welcome.html)
* [AWS CloudFormation](https://docs.aws.amazon.com/cloudformation/index.html)

---

