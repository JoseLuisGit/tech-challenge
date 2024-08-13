ssh -o StrictHostKeyChecking=no ${AWS_EC2_USER}@${AWS_EC2_HOST} docker exec -it ${DEPLOYMENT_CONTAINER_NAME} php artisan db:seed
echo "[+] ${DEPLOYMENT_CONTAINER_NAME} seeded!"