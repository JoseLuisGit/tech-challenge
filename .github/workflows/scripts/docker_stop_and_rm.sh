
container_id=$(ssh -o StrictHostKeyChecking=no ${AWS_EC2_USER}@${AWS_EC2_HOST} docker ps -a -q -f "name=^${DEPLOYMENT_CONTAINER_NAME}$")

if [ -n "${container_id}" ]; then
    ssh -o StrictHostKeyChecking=no ${AWS_EC2_USER}@${AWS_EC2_HOST} docker stop --time 1000 ${container_id}
    ssh -o StrictHostKeyChecking=no ${AWS_EC2_USER}@${AWS_EC2_HOST} docker rm -f ${container_id}
fi
