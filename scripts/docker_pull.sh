ssh -o StrictHostKeyChecking=no ${AWS_EC2_USER}@${AWS_EC2_HOST} docker pull ${DOCKER_USER}/${DEPLOYMENT_CONTAINER_NAME}:${DEPLOYMENT_IMAGE_TAG}
echo "[+] ${DEPLOYMENT_CONTAINER_NAME} pulled in ${AWS_EC2_HOST}"