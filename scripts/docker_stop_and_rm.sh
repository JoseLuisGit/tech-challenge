
container_id=$(ssh -o StrictHostKeyChecking=no ${AWS_EC2_USER}@${AWS_EC2_HOST} docker ps -a -q -f "name=^${DEPLOYMENT_CONTAINER_NAME}$")

if [ -n "${container_id}" ]; then
    ssh -o StrictHostKeyChecking=no ${AWS_EC2_USER}@${AWS_EC2_HOST} docker stop ${container_id}
    ssh -o StrictHostKeyChecking=no ${AWS_EC2_USER}@${AWS_EC2_HOST} docker rm -f ${container_id}
    echo "[+] ${DEPLOYMENT_CONTAINER_NAME} stopped and removed in ${AWS_EC2_HOST}"
fi
