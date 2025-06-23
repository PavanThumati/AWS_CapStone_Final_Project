# Use official Python image
FROM python:3.11-slim

# Set environment variables
ENV PYTHONDONTWRITEBYTECODE=1
ENV PYTHONUNBUFFERED=1

# Set working directory
WORKDIR /app

# Install system dependencies for cryptography
RUN apt-get update && \
    apt-get install -y build-essential libssl-dev libffi-dev python3-dev && \
    apt-get clean

# Install Python packages
RUN pip install --no-cache-dir Flask==3.0.2 PyMySQL==1.1.0 cryptography==42.0.5

# Copy your app files
COPY . .

# Expose the Flask port
EXPOSE 5000

# Run your app
CMD ["python", "app.py"]
