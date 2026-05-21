#!/bin/bash

# Build script for production Docker image
# This script prepares the application for production deployment

set -e

echo "🔨 Building JobsToFind for Production..."

# Build the Docker image
docker build -t jobstofind:latest .

# Optional: Tag for registry (e.g., Docker Hub, GitHub Container Registry)
# docker tag jobstofind:latest yourusername/jobstofind:latest
# docker push yourusername/jobstofind:latest

echo "✅ Production build complete!"
echo ""
echo "To run the build:"
echo "  docker run -d --name jobstofind -p 80:80 jobstofind:latest"
echo ""
echo "To push to registry:"
echo "  docker tag jobstofind:latest yourusername/jobstofind:latest"
echo "  docker push yourusername/jobstofind:latest"
