<nav id="menu">
    <ul>
        <li>
            <form action='index.php' method='post' >
                <input type='submit' value='Empleados' tabindex="1" alt="Gestor de Empleados" title="Gestor de Empleados" />
                <input class="oculto" name='indice' type='text' value='1' />
            </form>                    
        </li>
        <li>
            <form action='index.php' method='post' >
                <input type='submit' value='Grupos' tabindex="2" alt="Gestor de Grupos" title="Gestor de Grupos" />
                <input class="oculto" name='indice' type='text' value='2' />
            </form>                    
        </li>
        <li>
            <form action='index.php' method='post' >
                <input type='submit' value='Documentos' tabindex="3" alt="Gestor de Documentos" title="Gestor de Documentos" />
                <input class="oculto" name='indice' type='text' value='3' />
            </form>                    
        </li>
        <li>
            <form action='index.php' method='post' >
                <input type='submit' value='Envíos' tabindex="4" alt="Gestor de Envíos"  title="Gestor de Envíos"/>
                <input class="oculto" name='indice' type='text' value='4' />
            </form>                    
        </li>
        <li>
            <form action='index.php' method='post' >
                <input type='submit' value='Cuentas Email' tabindex="5" alt="Gestor de Cuentas de Email" title="Gestor de Cuentas de Email" />
                <input class="oculto" name='indice' type='text' value='5' />
            </form>                    
        </li>
        <li>
            <form action='index.php' method='post' >
                <input type='submit' value='Acceso' tabindex="6" alt="Gestor de Accesos" title="Gestor de Accesos" />
                <input class="oculto" name='indice' type='text' value='6' />
            </form>                    
        </li>
        <li>
            <form action='login.php' method='post' >
                <input type='submit' tabindex="7" value="<?php echo 'Desconectar (' . textoElipsis($nombreUsuario, 15) . ')' ?>" alt="<?php echo 'Desconectar (' . textoElipsis($nombreUsuario, 15) . ')' ?>" />
                <input class="oculto" name='clear' type='text' value='1' />
            </form>                    
        </li>                
    </ul>
</nav>        
<hr />