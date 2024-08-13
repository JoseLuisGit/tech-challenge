# Tech Challenge
 This is an app rest using data from the Government of Mexico City for municipalities.

You can access it from: http://ec2-3-149-233-254.us-east-2.compute.amazonaws.com

## Operations
### GET - /price-m2/zip-codes/{zip_code}/aggregate/{type}

This is an API to obtain the aggregate price (average, minimum and maximum) per m2 of a zip code of the Álvaro Obregón municipality using data from the Government of Mexico City

#### Parameters:
- zip_code (path): required 
- type (path): required only (min, max or avg)
- cve_vus (query): required only (A, C or E)

#### Response
- type: type of aggregated operation as average, minimum and maximum.
- price_unit: is the unit price.
- price_unit_construction: is the unit price considering the construction.
- elements: values ​​on which the operation was performed.

## Instalation
### Development
Using docker compose:
- Create .env from .env.example
- Execute ``` docker compose up -d ```

Using docker image:
- Create .env from .env.example
- Execute ``` docker build -t tech-challenge:latest . ```
- Execute ``` docker run -d -p 80:8000 --env-file ./.env -v ./.env:/app/.env --restart unless-stopped --name tech tech-challenge:latest```

### Staging or Production
Currently, an AWS EC2 instance is configured
- Configure secrets and variables in github actions
- Go to the actions tab > Deployment > Run workflow
- Select the main branch (Recommended)
- Additionally, you have these options:
  - Run migrations (Will run the migrations as an additional step, by default it is true)
  - Run seeds (Will run the seeders as an additional step, by default it is false)

## Actions
- Test execution
- Build and upload the docker image to a public repository

## For Improvement
- Versioning of docker images
- Makes use of "release/" and "hotfix/" branches
