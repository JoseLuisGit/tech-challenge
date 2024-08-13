ssh -o StrictHostKeyChecking=no ${AWS_EC2_USER}@${AWS_EC2_HOST} docker run -d \
          -p 80:8000 \
          --env-file ./.env \
          -v ./.env:/app/.env \
          --restart unless-stopped \
          --name ${DEPLOYMENT_CONTAINER_NAME}  \
          ${DEPLOYMENT_CONTAINER}:${DEPLOYMENT_IMAGE_TAG}