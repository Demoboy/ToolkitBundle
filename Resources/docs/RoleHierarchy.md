Role Hierarchy
----------------------------------

This bundle comes with a service that attaches itself to the security.role_hierarchy service
This listener will provide the Symfony security service with a role map that 
allows the security service to make more intelagant decisions when using is_granted.
It does this by pulling all the roles in the database and telling the service which roles have more access 
than the others. The default hierarchy is as follows:

    ROLE_SUPER_ADMIN -> ROLE_ADMIN -> ROLE_USER

This will allow you to only have to add a single role to a user to inherit the sub-roles;
a user with the "ROLE_ADMIN" role will have all the access of "ROLE_USER" and of "ROLE_ADMIN", so in example
is_granted("ROLE_USER") will return true. To determine if a user has a role impleicatly, you can use a helper method
on the user object "hasRoleByName"

It is recomended that when creating new roles you use ROLE_SUPER_ADMIN as the parent to ensure that the 
super user gets the role automatically (or you can change fixtures).
