<div class="menu">
    <ul>
        <li>
            <form action='index.php' method='post' title="Gestor de Empleados" id="empleados">
                <input type='submit' value='Empleados' tabindex="1" title="Gestor de Empleados" />
                <input class="oculto" name='indice' type='hidden' value='1' />
            </form>                    
        </li>
        <li>
            <form action='index.php' method='post' title="Gestor de Grupos" id="grupos">
                <input type='submit' value='Grupos' tabindex="2" title="Gestor de Grupos" />
                <input class="oculto" name='indice' type='hidden' value='2' />
            </form>                    
        </li>
        <li>
            <form action='index.php' method='post' title="Gestor de Documentos" id="documentos">
                <input type='submit' value='Documentos' tabindex="3" title="Gestor de Documentos" />
                <input class="oculto" name='indice' type='hidden' value='3' />
            </form>                    
        </li>
        <li>
            <form action='index.php' method='post' title="Gestor de Envíos" id="envios">
                <input type='submit' value='Envíos' tabindex="4" title="Gestor de Envíos"/>
                <input class="oculto" name='indice' type='hidden' value='4' />
            </form>                    
        </li>
        <li>
            <form action='index.php' method='post' title="Gestor de Cuentas de Email" id="emails">
                <input type='submit' value='Cuentas Email' tabindex="5" title="Gestor de Cuentas de Email" />
                <input class="oculto" name='indice' type='hidden' value='5' />
            </form>                    
        </li>
        <li>
            <form action='index.php' method='post' title="Gestor de Accesos" id="accesos">
                <input type='submit' value='Usuarios' tabindex="6" title="Gestor de Usuarios" />
                <input class="oculto" name='indice' type='hidden' value='6' />
            </form>                    
        </li>
        <li>
            <form action='login.php' method='post' title="Desconectar la sesión" id="logout">
                <input type='submit' tabindex="7" title="Desconectar la sesión" value="<?php echo 'Desconectar (' . textoElipsis($nombreUsuario, 15) . ')' ?>" />
                <input class="oculto" name='clear' type='hidden' value='1' />
            </form>                    
        </li>                
    </ul>
</div>        
<hr />