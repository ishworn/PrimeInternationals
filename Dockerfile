# Stage 1: Use the first image with SHA256 hash
FROM sha256:088eea90c3d0a540ee5686e7d7471acbd4063b6e97eaf49b5e651665eb7f4dc7 AS stage1
# Perform any specific operations related to this image, if needed
# COPY your files or configure things based on this image

# Stage 2: Use the second image with SHA256 hash
FROM sha256:951d41308dedcee1ec253333e917cec73b4eefbc780add5ea16b30caa905250a AS stage2
# Perform any specific operations related to this image, if needed
# COPY your files or configure things based on this image

# Stage 3: Use the third image with SHA256 hash
FROM sha256:ecbdfabe0fc1ee8a254606062dbfede2b827a0b8724c9131e704a0c088338590 AS stage3
# Perform any specific operations related to this image, if needed
# COPY your files or configure things based on this image

# Final Stage: Combine everything together from previous stages
FROM ubuntu:20.04  # You can choose a base image for the final result

# Copy the necessary files from all the previous stages
COPY --from=stage1 /path_from_stage1 /path_to_copy_to
COPY --from=stage2 /path_from_stage2 /path_to_copy_to
COPY --from=stage3 /path_from_stage3 /path_to_copy_to

# Set up your environment if needed
# Example: setting environment variables
# ENV KEY=value

# Expose any necessary ports
EXPOSE 80

# Set the default command to run
CMD ["php-fpm"]

