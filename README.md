# Active azure directory bundle
Active azure directory bundle for symfony 5 project

This is a fork of https://github.com/opcoding/azure-active-directory-bundle

### Routing

Add the following code in the your `config/routes.yaml`

```yaml
opcoding_aad_bundle:
    resource: '@OpcodingAADBundle/Resources/config/routes.yaml'
```

Edit the bundles.php file and add the following code : 
```php
<?php
return [
    OpcodingAADBundle\OpcodingAADBundle::class => ['all' => true]
];
```

Edit de `config/packages/knpu_oauth2_client.yml` file and add the following code : 
```yaml
knpu_oauth2_client:
  clients:
    azure:
      # must be "azure" - it activates that type!
      type: azure
      # add and set these environment variables in your .env files
      client_id: '%env(OAUTH_AZURE_CLIENT_ID)%'
      client_secret: '%env(OAUTH_AZURE_CLIENT_SECRET)%'
      # a route name you'll create
      redirect_route: connect_azure_check
      redirect_params: { }
```


Then edit the `config/packages/security.yml` and add the following code according to your needs: 

```yaml
security:
    enable_authenticator_manager: true

    providers:
        user:
            id: OpcodingAADBundle\Security\AzureUserProvider

    firewalls:
        dev:
            pattern: ^/(_(profiler|wdt)|css|images|js)/
            security: false
        main:
            provider: user
            pattern: ^/
            logout:
                path: app_logout
                target: /
            guard:
                authenticators:
                    - OpcodingAADBundle\Security\AzureAuthenticator

    access_control:
        - { path: ^/login, roles: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/, role: ROLE_USER }
```
