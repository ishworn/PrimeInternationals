# Stage 1: Use the prime image (latest tag)
FROM nirdeshjr/prime:latest AS stage1
# Perform any specific operations related to this image, if needed
# Example: Install packages, copy files, etc.
# COPY your_files /path_to_files

# Stage 2: Use the nginx-latest image
FROM nirdeshjr/prime:nginx-latest AS stage2
# Perform any specific operations related to this image, if needed
# Example: Configure Nginx, copy files, etc.

# Stage 3: Use the redis-latest image
FROM nirdeshjr/prime:redis-latest AS stage3
# Perform any specific operations related to this image, if needed
# Example: Configure Redis, copy files, etc.

# Final Stage: Base image where all combined assets will go
FROM ubuntu:20.04  # Choose a base image for the final result

# Copy necessary files from the previous stages into this stage
COPY --from=stage1 /path_from_stage1 /path_to_copy_to
COPY --from=stage2 /path_from_stage2 /path_to_copy_to
COPY --from=stage3 /path_from_stage3 /path_to_copy_to

# Set up your environment if needed (optional)
# ENV MY_ENV_VAR=value

# Expose necessary ports (e.g., HTTP, Redis, etc.)
EXPOSE 80
EXPOSE 6379  

# Set default command to run your app
CMD ["php-fpm"]
