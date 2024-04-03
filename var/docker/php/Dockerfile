ARG PHP_TAG
ARG DRUPAL_CORE_CONSTRAINT
# This will allow to run tests without sudo.
ARG PHP_FPM_USER=wodby
ARG PHP_FPM_GROUP=wodby

FROM wodby/php:${PHP_TAG}

USER root
RUN mkdir /var/www/contrib
RUN chown wodby:wodby /var/www/contrib
COPY build-composer-json.php /usr/local/bin/build-composer-json
RUN chown wodby:wodby /usr/local/bin/build-composer-json
RUN chmod +x /usr/local/bin/build-composer-json

USER wodby
# Install chi-teck/drupalrc.
RUN wget -O $HOME/.drupalrc https://raw.githubusercontent.com/Chi-teck/drupalrc/master/drupalrc
RUN echo source $HOME/.drupalrc >> $HOME/.bashrc
RUN sed -i 's/sudo -uwww-data -E //' $HOME/.drupalrc
RUN wget -O $HOME/.drupal.complete.sh https://raw.githubusercontent.com/Chi-teck/drupalrc/master/drupal.complete.sh
RUN echo source $HOME/.drupal.complete.sh >> $HOME/.bash_completion

COPY prepare-environment.sh /docker-entrypoint-init.d/
