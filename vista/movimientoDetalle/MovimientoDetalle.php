<?php
/**
 *@package pXP
 *@file AlmacenStock.php
 *@author  Gonzalo Sarmiento
 *@date 01-10-2012
 *@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
 */

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
	Phx.vista.MovimientoDetalle = Ext.extend(Phx.gridInterfaz, {
		constructor : function(config) {
			this.maestro = config.maestro;
			Phx.vista.MovimientoDetalle.superclass.constructor.call(this, config);
			this.init();
			this.grid.getTopToolbar().disable();
			this.grid.getBottomToolbar().disable();
			this.store.removeAll();
		},
		Atributos : [{
			config : {
				labelSeparator : '',
				inputType : 'hidden',
				name : 'id_movimiento_det'
			},
			type : 'Field',
			form : true
		}, {
			config : {
				name : 'id_movimiento',
				labelSeparator : '',
				inputType : 'hidden',
			},
			type : 'Field',
			form : true
		}, {
			config : {
				name : 'id_item',
				fieldLabel : 'Item',
				allowBlank : false,
				emptyText : 'Item...',
				store : new Ext.data.JsonStore({
					url : '../../sis_almacenes/control/Item/listarItemNotBase',
					id : 'id_item',
					root : 'datos',
					sortInfo : {
						field : 'nombre',
						direction : 'ASC'
					},
					totalProperty : 'total',
					fields : ['id_item', 'nombre'],
					remoteSort : true,
					baseParams : {
						par_filtro : 'nombre'
					}
				}),
				valueField : 'id_item',
				displayField : 'nombre',
				gdisplayField : 'nombre_item',
				//tpl : '<tpl for="."><div class="x-combo-list-item"><p>{nombre}</p> </div></tpl>',
				hiddenName : 'id_item',
				forceSelection : true,
				typeAhead : true,
				triggerAction : 'all',
				lazyRender : true,
				mode : 'remote',
				pageSize : 10,
				queryDelay : 1000,
				anchor : '100%',
				gwidth : 100,
				minChars : 2,
				renderer : function(value, p, record) {
					return String.format('{0}', record.data['nombre_item']);
				}
			},
			type : 'ComboBox',
			id_grupo : 0,
			filters : {
				pfiltro : 'item.nombre',
				type : 'string'
			},
			grid : true,
			form : true
		}, {
			config : {
				name : 'cantidad_item',
				fieldLabel : 'Cantidad',
				allowBlank : false,
				anchor : '100%',
				gwidth : 70,
				maxLength : 6
			},
			type : 'NumberField',
			filters : {
				pfiltro : 'movdet.cantidad',
				type : 'numeric'
			},
			grid : true,
			form : true
		}, {
			config : {
				name : 'costo_unitario',
				fieldLabel : 'Costo unitario',
				allowBlank : false,
				anchor : '100%',
				gwidth : 90,
				maxLength : 10
			},
			type : 'NumberField',
			filters : {
				pfiltro : 'movdet.costo_unitario',
				type : 'numeric'
			},
			grid : true,
			form : true
		}, {
			config : {
				name : 'fecha_caducidad',
				fieldLabel : 'Fecha de caducidad',
				allowBlank : true,
				renderer : function(value, p, record) {
				    return value ? value.dateFormat('d/m/Y') : ''
				},
				format : 'Y-m-d'
			},
			type : 'DateField',
			filters : {
				pfiltro : 'movdet.fecha_caducidad',
				type : 'date'
			},
			id_grupo : 1,
			grid : true,
			form : true
		}, {
			config : {
				name : 'usr_reg',
				fieldLabel : 'Usuario Reg.',
				gwidth : 100,
			},
			type : 'TextField',
			filters : {
                pfiltro : 'usu1.cuenta',
                type : 'string'
            },
			grid : true,
			form : false
		}, {
			config : {
				name : 'fecha_reg',
				fieldLabel : 'Fecha registro',
				gwidth : 110,
				renderer : function(value, p, record) {
				    return value ? value.dateFormat('d/m/Y h:i:s') : ''
				}
			},
			type : 'DateField',
			filters : {
				pfiltro : 'movdet.fecha_reg',
				type : 'date'
			},
			grid : true,
			form : false
		}, {
			config : {
				name : 'usr_mod',
				fieldLabel : 'Usuario mod.',
				gwidth : 100,
			},
			type : 'TextField',
			filters : {
                pfiltro : 'usu2.cuenta',
                type : 'string'
            },
			grid : true,
			form : false
		}, {
			config : {
				name : 'fecha_mod',
				fieldLabel : 'Fecha de modif.',
				gwidth : 110,
				renderer : function(value, p, record) {
				    return value ? value.dateFormat('d/m/Y') : ''
				}
			},
			type : 'DateField',
			filters : {
                pfiltro : 'movdet.fecha_mod',
                type : 'date'
            },
			grid : true,
			form : false
		}],
		title : 'Detalle de Movimiento',
		ActSave : '../../sis_almacenes/control/MovimientoDetalle/insertarMovimientoDetalle',
		ActDel : '../../sis_almacenes/control/MovimientoDetalle/eliminarMovimientoDetalle',
		ActList : '../../sis_almacenes/control/MovimientoDetalle/listarMovimientoDetalle',
		id_store : 'id_movimiento_det',
		fields : [{
			name : 'id_movimiento_det',
			type : 'numeric'
		}, {
			name : 'id_movimiento',
			type : 'numeric'
		}, {
			name : 'id_item',
			type : 'numeric'
		}, {
			name : 'nombre_item',
			type : 'string'
		}, {
			name : 'cantidad_item',
			type : 'numeric'
		}, {
			name : 'costo_unitario',
			type : 'numeric'
		}, {
			name : 'fecha_caducidad',
			type : 'date',
			dateFormat : 'Y-m-d'
		}, {
			name : 'usr_reg',
			type : 'string'
		}, {
			name : 'fecha_reg',
			type : 'date',
			dateFormat : 'Y-m-d H:i:s.u'
		}, {
			name : 'usr_mod',
			type : 'string'
		}, {
			name : 'fecha_mod',
			type : 'date',
			dateFormat : 'Y-m-d H:i:s.u'
		}],
		sortInfo : {
			field : 'id_movimiento_det',
			direction : 'ASC'
		},
		bsave : false,
		onReloadPage : function(m) {
			this.maestro = m;
			this.Atributos[1].valorInicial = this.maestro.id_movimiento;
			if (this.maestro.estado_mov == 'finalizado') {
			    this.getBoton('edit').hide();
			    this.getBoton('del').hide();
			    this.getBoton('new').hide();
			} else {
			    this.getBoton('edit').show();
                this.getBoton('del').show();
                this.getBoton('new').show();
			}
			if (m.id != 'id') {
				this.store.baseParams = {
					id_movimiento : this.maestro.id_movimiento
				};
				this.load({
					params : {
						start : 0,
						limit : 50
					}
				});
			} else {
				this.grid.getTopToolbar().disable();
				this.grid.getBottomToolbar().disable();
				this.store.removeAll();
			}
		}
	})
</script>
