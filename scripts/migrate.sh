ssh -o StrictHostKeyChecking=no ${AWS_EC2_USER}@${AWS_EC2_HOST} docker exec -it ${DEPLOYMENT_CONTAINER_NAME} php artisan migrate
echo "[+] ${DEPLOYMENT_CONTAINER_NAME} migrated!"