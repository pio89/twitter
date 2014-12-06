-- CARGA DATOS PARA PRUEBAS TWITTER ON LINE --
-- Partimos de la BBDD vacía --

-- ROLES --

    insert into usuarios (nick, password)
                      values ('antonio',
                             md5('antonio'));


    insert into tuit (mensaje,id_usuarios)
                      values ('Hoy me siento feliz. Ironia modo on', 1);


                  

