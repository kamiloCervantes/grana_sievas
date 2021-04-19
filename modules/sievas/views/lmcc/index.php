       <!-- top tiles -->
          <div class="row tile_count">
            <div class="col-md-3 col-sm-3 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-user"></i> Ev. Realizadas</span>
              <div class="count"><?php echo count($evaluaciones); ?></div>
              <!--<span class="count_bottom"><i class="green">4% </i> From last Week</span>-->
            </div>
            <div class="col-md-3 col-sm-3 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-clock-o"></i> Promedio General</span>
              <div class="count"><?php echo $pr_general; ?></div>
<!--              <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>3% </i> From last Week</span>-->
            </div>
            <div class="col-md-3 col-sm-3 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-user"></i> Ev. mayor promedio</span>
              <div class="count green"><?php echo $pr_mayor; ?></div>
<!--              <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>34% </i> From last Week</span>-->
            </div>
            <div class="col-md-3 col-sm-3 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-user"></i> Evaluaciones activas</span>
              <div class="count"><?php echo count($ev_activas); ?></div>
<!--              <span class="count_bottom"><i class="red"><i class="fa fa-sort-desc"></i>12% </i> From last Week</span>-->
            </div>
          </div>
          <!-- /top tiles -->

          <div class="row">
              <div class="page hide" data-page="#inicio">
             <div class="dashboard_graph">
            <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                <!-- Wrapper for slides -->
                <div class="carousel-inner" role="listbox">
                  <div class="item active">
                     <div class="col-md-12 col-sm-12 col-xs-12">
                            <div id="grafico1" style="width: 100%"></div>
                          </div>
                  </div>
                  <div class="item">
                   <div class="col-md-12 col-sm-12 col-xs-12" style="text-align: center">
<!--                           <div id="grafico2" style="width: 80%"></div>-->
                        <div id="grafindicametro1" style="width: 80%"></div>
                    </div>
                  </div>
                  <div class="item">
                     <div class="col-md-12 col-sm-12 col-xs-12">
                            <div id="grafico2" style="width: 80%"></div>
                          </div>
                  </div>
                  <div class="item">
                   <div class="col-md-12 col-sm-12 col-xs-12" style="text-align: center">
<!--                           <div id="grafico2" style="width: 80%"></div>-->
                        <div id="grafindicametro2" style="width: 80%"></div>
                    </div>
                  </div>
<!--                  <div class="item">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                            <div id="grafico4" style="width: 80%"></div>
                          </div>
                  </div>-->
                    <div class="clearfix"></div>
                        </div>
                
                 <!-- Controls -->
                <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
                  <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                  <span class="sr-only">Previous</span>
                </a>
                <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                  <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                  <span class="sr-only">Next</span>
                </a>
              </div>
              </div>
            <!-- /carousel inicio -->

            </div><!-- /page inicio -->
            <div class="page hide" data-page="#avances">
                <p>Avances</p>
            </div>
            <div class="page hide" data-page="#grafindicametros">
                <div id="carousel-grafindicametros" class="carousel slide" data-ride="carousel">
                <!-- Wrapper for slides -->
                <div class="carousel-inner" role="listbox">
                  <?php foreach($evaluaciones as $kev => $ev){ ?>
                  <?php if($kev > 0){ ?>
                    <div class="item">
                    <div class="col-md-12 col-sm-12 col-xs-12" style="text-align: center">
                        <div id="grafindicametro_esp_<?php echo $kev ?>" style="width: 80%"></div>
                     </div>
                   </div>
                    <div class="item">
                    <div class="col-md-12 col-sm-12 col-xs-12" style="text-align: center">
                        <div id="grafindicametro_ext_<?php echo $kev ?>" style="width: 80%"></div>
                     </div>
                   </div>
                    
                  <?php } else { ?>
                     <div class="item active">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                               <div id="grafindicametro_esp_<?php echo $kev ?>" style="width: 100%"></div>
                             </div>
                     </div>
                    <div class="item">
                    <div class="col-md-12 col-sm-12 col-xs-12" style="text-align: center">
                        <div id="grafindicametro_ext_<?php echo $kev ?>" style="width: 80%"></div>
                     </div>
                   </div>
                  <?php } ?>
                    
                 
                  
                  <?php } ?>
                
<!--                  <div class="item">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                            <div id="grafico4" style="width: 80%"></div>
                          </div>
                  </div>-->
                    <div class="clearfix"></div>
                        </div>
                
                 <!-- Controls -->
                <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
                  <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                  <span class="sr-only">Previous</span>
                </a>
                <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                  <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                  <span class="sr-only">Next</span>
                </a>
              </div>
            </div>
            <div class="page hide" data-page="#estadisticas">
                <div id="carousel-estadisticas" class="carousel slide" data-ride="carousel">
                <!-- Wrapper for slides -->
                <div class="carousel-inner" role="listbox">
                  <?php foreach($evaluaciones as $kev => $ev){ ?>
                  <?php if($kev > 0){ ?>
                    <div class="item">
                    <div class="col-md-12 col-sm-12 col-xs-12" style="text-align: center">
                        <div id="linea_esp_<?php echo $kev ?>" style="width: 80%"></div>
                     </div>
                   </div>
                    
                    
                  <?php } else { ?>
                     <div class="item active">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                               <div id="linea_esp_<?php echo $kev ?>" style="width: 100%"></div>
                             </div>
                     </div>
                   
                  <?php } ?>
                    
                 
                  
                  <?php } ?>
                
<!--                  <div class="item">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                            <div id="grafico4" style="width: 80%"></div>
                          </div>
                  </div>-->
                    <div class="clearfix"></div>
                        </div>
                
                 <!-- Controls -->
                <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
                  <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                  <span class="sr-only">Previous</span>
                </a>
                <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                  <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                  <span class="sr-only">Next</span>
                </a>
              </div>
            </div>
            <div class="page hide" data-page="#rankings">
                <p>Ranking</p>
            </div>
            <div class="page hide" data-page="#promedios">
                 <hr/>
                <h2 style="display: inline">Promedios de acreditación</h2>
                <select style="display: inline;">
                    <?php foreach($opciones as $opc){ ?>
                    <option value="<?php echo $opc['id']?>"><?php echo $opc['opcion']?></option>
                    <?php } ?>
                </select>
                <hr/>
                <table class="table">
                    <tr>
                        <td>Promedio Copaes</td>
                        <td>8.55</td>
                    </tr>
                    <tr>
                        <td>Promedio Ciies</td>
                        <td>9.31</td>
                    </tr>
                    <tr>
                        <td>Promedio nacional (Copaes-Ciies)</td>
                        <td>8.92</td>
                    </tr>
                    <tr>
                        <td>Promedio internacional (Grana)</td>
                        <td>8.07</td>
                    </tr>
                    <tr>
                        <td>Promedio nacional-internacional</td>
                        <td>8.54</td>
                    </tr>
                </table>
            </div>
            <div class="page hide" data-page="#cambiarunidad">
                <div class="list-group">
                <?php foreach($unidades as $un){ ?>                
                    <a href="index.php?mod=sievas&controlador=lmcc&accion=index&i=<?php echo $un['cod_institucion'] ?>" class="list-group-item">
                      <?php echo $un['nom_institucion'] ?>
                    </a>              
                <?php } ?>
                </div>
            </div>
          </div>
          <br />

<input type="hidden" id="institucion_id" value="<?php echo $ins ?>" />