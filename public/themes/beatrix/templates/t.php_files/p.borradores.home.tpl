{include "main_header.tpl"}

<div class="container">
   <div class="row">
      <div class="col-3">
         <div class="boxy">
                                <div class="boxy-title">
                                    <h3>Filtrar</h3>
                                    <span></span>
                                </div><!-- boxy-title -->
                                <div class="boxy-content">
                                    <h4>Mostrar</h4>
                    
                                    
                                    <h4>Ordenar por</h4>
                    
                                    <ul id="borradores-orden" class="cat-list">
                                        <li class="active"><span><a href="" onclick="borradores.active(this); borradores.orden = 'fecha_guardado'; borradores.query(); return false;">Fecha guardado</a></span></li>
                                        <li><span><a href="" onclick="borradores.active(this); borradores.orden = 'titulo'; borradores.query(); return false;">T&iacute;tulo</a></span></li>
                                        <li><span><a href="" onclick="borradores.active(this); borradores.orden = 'categoria'; borradores.query(); return false;">Categor&iacute;a</a></span></li>
                                    </ul>
                                    <h4>Categorias</h4>
                    
                                    <ul class="cat-list" id="borradores-categorias">
                                        <li id="todas" class="active"><span class="cat-title active"><a href="" onclick="borradores.active(this); borradores.categoria = 'todas'; borradores.query(); return false;">Ver todas</a></span> <span class="count"></span></li>
                                    </ul>
                                </div><!-- boxy-content -->
                            </div>
                        </div>
                        <div class="col-9">
                            <div class="boxy">
                                <div class="boxy-title">
                                    <h3>Posts</h3>
                                    <label for="borradores-search" style="color:#999999;float:right;position:absolute;right:135px;top:11px;z-index:5;">Buscar</label><input type="text" id="borradores-search" value="" onKeyUp="borradores.search(this.value, event)" onFocus="borradores.search_focus()" onBlur="borradores.search_blur()" autocomplete="off" />
                                </div>
                                <div id="res" class="boxy-content">
                                 	{if $tsDrafts}<ul id="resultados-borradores"></ul>{else}
                                    <div class="emptyData">No tienes ning&uacute;n borrador ni post eliminado.</div>{/if}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="template-result-borrador" style="display:none">
                        <li id="borrador_id___id__">
                            <a title="__categoria_name__" class="categoriaPost __categoria__ __tipo__" href="__url__" onclick="__onclick__" style="background-image:url({$tsConfig.images}/icons/cat/__imagen__)">__titulo__</a>
                            <span class="causa">Causa: __causa__</span>
                            <span class="gray">&Uacute;ltima vez guardado el __fecha_guardado__</span> <a style="float:right" href="" onclick="borradores.eliminar(__borrador_id__, true); return false;"><img src="http://o2.t26.net/images/borrar.png" alt="eliminar" title="Eliminar Borrador" /></a>
                    
                        </li>
                    </div>
                </div>
                <div style="clear:both"></div>
            
{include "main_footer.tpl"}