<?php
/**
*@package pXP
*@file PreingresoDetModActV2.php
*@author  RCM
*@date 08/08/2017
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.PreingresoDetModActV2=Ext.extend(Phx.gridInterfaz,{
	estado: 'mod',
	constructor:function(config){
		this.maestro=config.maestro;
    	//llama al constructor de la clase padre
		Phx.vista.PreingresoDetModActV2.superclass.constructor.call(this,config);
		this.grid.getTopToolbar().disable();
		this.grid.getBottomToolbar().disable();
		this.grid.addListener('cellclick', this.oncellclick,this);
		this.init();

		//Se agrega el botón para adicionar todos
		this.addButton('btnAgTodos', {
			text : 'Quitar Todos',
			iconCls : 'bleft-all',
			disabled : true,
			handler : this.quitarTodos,
			tooltip : '<b>Quitar Todos</b><br/>Quita todos los items del preingreso.'
		});

		//Se agrega el botón para adicionar todos
		this.addButton('btnDesgl', {
			text : 'Desglosar',
			iconCls : 'bgear',
			disabled : true,
			handler : this.desglosar,
			tooltip : '<b>Desglosar registro</b><br/>Desglosa el registro en la cantidad comprada'
		});
	},

	Atributos:[
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_preingreso_det'
			},
			type:'Field',
			form:true
		},
		{
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_preingreso'
			},
			type:'Field',
			form:true
		},
		{
            config:{
                name: 'quitar',
                fieldLabel: 'Quitar',
                allowBlank: true,
                anchor: '80%',
                gwidth: 50,
                scope: this,
                renderer:function (value, p, record, rowIndex, colIndex){
					return "<div style='text-align:center'><img border='0' style='-webkit-user-select:auto;cursor:pointer;' title='Quitar' src = '../../../lib/imagenes/icono_awesome/awe_left_arrow.png' align='center' width='30' height='30'></div>";
                }
            },
            type:'Checkbox',
            id_grupo:1,
            grid:true,
            form:false
        },
		{
            config:{
                name: 'agregar',
                fieldLabel: 'Incluido',
                allowBlank: true,
                anchor: '80%',
                gwidth: 50,
                scope: this,
                renderer:function (value, p, record, rowIndex, colIndex){
					return "<div style='text-align:center'><img border='0' style='-webkit-user-select:auto;cursor:pointer;' src = '../../../lib/imagenes/icono_awesome/awe_ok.png' align='center' width='30' height='30'></div>";
                }
            },
            type:'Checkbox',
            id_grupo:1,
            grid:true,
            form:false
        },
		{
			config:{
				name: 'cantidad_det',
				fieldLabel: 'Cantidad',
				allowBlank: true,
				anchor: '80%',
				gwidth: 55,
				maxLength:200
			},
			type:'NumberField',
			filters:{pfiltro:'predet.cantidad_det',type:'numeric'},
			id_grupo:1,
			grid:true,
			form:true
		},
		{
			config:{
				name: 'precio_compra',
				fieldLabel: 'Costo al 100%',
				allowBlank: true,
				anchor: '80%',
				gwidth: 75,
				maxLength:1179650
			},
			type:'NumberField',
			filters:{pfiltro:'predet.precio_compra',type:'numeric'},
			id_grupo:1,
			grid:true,
			form:true
		},

		{
			config:{
				name: 'precio_compra_87',
				fieldLabel: 'Costo',
				allowBlank: true,
				anchor: '80%',
				gwidth: 75,
				maxLength:1179650
			},
			type:'NumberField',
			filters:{pfiltro:'predet.precio_compra_87',type:'numeric'},
			id_grupo:1,
			grid:true,
			form:true
		},
		{
			config : {
				name : 'id_item',
				fieldLabel : 'Item',
				allowBlank : false,
				emptyText : 'Elija un Item...',
				store : new Ext.data.JsonStore({
					url : '../../sis_almacenes/control/Item/listarItemNotBase',
					id : 'id_item',
					root : 'datos',
					sortInfo : {
						field : 'nombre',
						direction : 'ASC'
					},
					totalProperty : 'total',
					fields : ['id_item', 'nombre', 'codigo', 'desc_clasificacion', 'codigo_unidad'],
					remoteSort : true,
					baseParams : {
						par_filtro : 'item.nombre#item.codigo#cla.nombre'
					}
				}),
				valueField : 'id_item',
				displayField : 'nombre',
				gdisplayField : 'desc_item',
				tpl : '<tpl for="."><div class="x-combo-list-item"><p>Nombre: {nombre}</p><p>Código: {codigo}</p><p>Clasif.: {desc_clasificacion}</p></div></tpl>',
				hiddenName : 'id_item',
				forceSelection : true,
				typeAhead : false,
				triggerAction : 'all',
				lazyRender : true,
				mode : 'remote',
				pageSize : 10,
				queryDelay : 1000,
				anchor : '100%',
				gwidth : 250,
				minChars : 2,
				turl : '../../../sis_almacenes/vista/item/BuscarItem.php',
				tasignacion : true,
				tname : 'id_item',
				ttitle : 'Items',
				tdata : {},
				tcls : 'BuscarItem',
				pid : this.idContenedor,
				renderer : function(value, p, record) {
					return String.format('{0}', record.data['desc_item']);
				},
				resizable: true
			},
			type : 'TrigguerCombo',
			id_grupo : 0,
			filters : {
				pfiltro : 'item.nombre',
				type : 'string'
			},
			grid : true,
			form : true
		},
		{
			config : {
				name : 'id_almacen',
				fieldLabel : 'Almacén',
				allowBlank : false,
				emptyText : 'Almacén...',
				store : new Ext.data.JsonStore({
					url : '../../sis_almacenes/control/Almacen/listarAlmacen',
					id : 'id_almacen',
					root : 'datos',
					sortInfo : {
						field : 'nombre',
						direction : 'ASC'
					},
					totalProperty : 'total',
					fields : ['id_almacen', 'nombre'],
					remoteSort : true,
					baseParams : {
						par_filtro : 'alm.nombre'
					}
				}),
				valueField : 'id_almacen',
				displayField : 'nombre',
				gdisplayField : 'desc_almacen',
				hiddenName : 'id_almacen',
				forceSelection : true,
				typeAhead : false,
				triggerAction : 'all',
				lazyRender : true,
				mode : 'remote',
				pageSize : 10,
				queryDelay : 1000,
				anchor : '100%',
				gwidth : 150,
				minChars : 2,
				renderer : function(value, p, record) {
					return String.format('{0}', record.data['desc_almacen']);
				}
			},
			type : 'ComboBox',
			id_grupo : 0,
			filters : {
				pfiltro : 'alm.codigo',
				type : 'string'
			},
			grid : true,
			form : true
		},
		{
			config: {
				name: 'id_clasificacion',
				fieldLabel: 'Clasificación',
				allowBlank: false,
				emptyText: 'Elija una opción...',
				store: new Ext.data.JsonStore({
                    url: '../../sis_kactivos_fijos/control/Clasificacion/ListarClasificacionTree',
                    id: 'id_clasificacion',
                    root: 'datos',
                    sortInfo: {
                        field: 'orden',
                        direction: 'ASC'
                    },
                    totalProperty: 'total',
                    fields: ['id_clasificacion','clasificacion', 'id_clasificacion_fk','tipo_activo','depreciable','vida_util',''],
                    remoteSort: true,
                    baseParams: {
                        par_filtro:'claf.clasificacion'
                    }
                }),
				valueField: 'id_clasificacion',
				displayField: 'clasificacion',
				gdisplayField: 'desc_clasificacion',
				hiddenName: 'id_clasificacion',
				forceSelection: true,
				typeAhead: false,
				triggerAction: 'all',
				lazyRender: true,
				mode: 'remote',
				pageSize: 15,
				queryDelay: 1000,
				anchor: '100%',
				gwidth: 150,
				minChars: 2
			},
			type: 'ComboBox',
			id_grupo: 0,
			filters: {pfiltro: 'cla.nombre',type: 'string'},
			grid: true,
			form: true
		},
		{
			config: {
				name: 'id_depto',
				fieldLabel: 'Depto.',
				allowBlank: false,
				emptyText: 'Elija una opción...',
				store: new Ext.data.JsonStore({
					url: '../../sis_parametros/control/Depto/listarDeptoFiltradoDeptoUsuario',
					id: 'id_',
					root: 'datos',
					sortInfo: {
						field: 'nombre',
						direction: 'ASC'
					},
					totalProperty: 'total',
					fields: ['id_depto', 'codigo', 'nombre'],
					remoteSort: true,
					baseParams: {par_filtro: 'DEPPTO.nombre#DEPPTO.codigo'}
				}),
				valueField: 'id_depto',
				displayField: 'nombre',
				gdisplayField: 'desc_depto',
				hiddenName: 'id_depto',
				forceSelection: true,
				typeAhead: false,
				triggerAction: 'all',
				lazyRender: true,
				mode: 'remote',
				pageSize: 15,
				queryDelay: 1000,
				anchor: '100%',
				gwidth: 150,
				minChars: 2,
				renderer : function(value, p, record) {
					return String.format('{0}', record.data['desc_depto']);
				},
				listWidth:300
			},
			type: 'ComboBox',
			id_grupo: 5,
			filters: {pfiltro: 'depto.nombre',type: 'string'},
			grid: true,
			form: true
		},
		{
			config:{
				name: 'nombre',
				fieldLabel: 'Nombre',
				allowBlank: false,
				anchor: '100%',
				gwidth: 180,
				maxLength:100
			},
			type:'TextArea',
			filters:{pfiltro:'predet.nombre',type:'string'},
			id_grupo:1,
			grid:true,
			form:true
		},
		{
			config:{
				name: 'descripcion',
				fieldLabel: 'Descripción',
				allowBlank: true,
				anchor: '100%',
				gwidth: 300,
				maxLength:2000
			},
			type:'TextArea',
			filters:{pfiltro:'predet.descripcion',type:'string'},
			id_grupo:1,
			grid:true,
			form:true
		},
		{
			config:{
				name: 'id_lugar',
				fieldLabel: 'Lugar',
				allowBlank: false,
				emptyText:'Lugar...',
				store:new Ext.data.JsonStore(
				{
					url: '../../sis_parametros/control/Lugar/listarLugar',
					id: 'id_lugar',
					root: 'datos',
					sortInfo:{
						field: 'nombre',
						direction: 'ASC'
					},
					totalProperty: 'total',
					fields: ['id_lugar','id_lugar_fk','codigo','nombre','tipo','sw_municipio','sw_impuesto','codigo_largo'],
					// turn on remote sorting
					remoteSort: true,
					baseParams:{par_filtro:'lug.nombre',tipo:'departamento'}
				}),
				valueField: 'id_lugar',
				displayField: 'nombre',
				gdisplayField:'nombre_lugar',
				hiddenName: 'id_lugar',
    			triggerAction: 'all',
    			lazyRender:true,
				mode:'remote',
				pageSize:50,
				queryDelay:500,
				anchor:"100%",
				gwidth:150,
				minChars:2,
				renderer:function (value, p, record){return String.format('{0}', record.data['nombre_lugar']);}
			},
			type:'ComboBox',
			filters:{pfiltro:'lug.nombre',type:'string'},
			id_grupo:0,
			grid:true,
			form:true
		},
		{
			config:{
				name: 'ubicacion',
				fieldLabel: 'Ubicación',
				allowBlank: false,
				anchor: '100%',
				gwidth: 180,
				maxLength:255
			},
			type:'TextArea',
			filters:{pfiltro:'predet.ubicacion',type:'string'},
			id_grupo:1,
			grid:true,
			form:true
		},
		{
            config:{
                name: 'id_centro_costo',
                origen: 'CENTROCOSTO',
                allowBlank: false,
                fieldLabel: 'Centro de Costo',
                gdisplayField: 'codigo_tcc',//mapea al store del grid
                gwidth: 150,
                url: '../../sis_parametros/control/CentroCosto/listarCentroCostoFiltradoXUsuaio',
                renderer: function (value, p, record){return String.format('{0}', record.data['codigo_tcc']);},
                baseParams:{filtrar:'grupo_ep'}
             },
            type: 'ComboRec',
            id_grupo: 1,
            filters: { pfiltro:'cc.descripcion_tcc',type:'string'},
            grid: true,
            form: true
        },
		{
			config:{
				name: 'c31',
				fieldLabel: 'C31',
				allowBlank: true,
				anchor: '100%',
				gwidth: 180,
				maxLength:255,
				hidden: true
			},
			type:'TextField',
			filters:{pfiltro:'predet.c31',type:'string'},
			id_grupo:1,
			grid:false,
			form:true
		},

		{
			config:{
				name: 'fecha_conformidad',
				fieldLabel: 'Fecha Ini/Dep',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				format: 'd/m/Y',
				renderer:function (value,p,record){return value?value.dateFormat('d/m/Y H:i:s'):''}
			},
			type:'DateField',
			filters:{pfiltro:'predet.fecha_conformidad',type:'date'},
			id_grupo:1,
			grid:true,
			form:true
		},
		{
			config:{
				name: 'fecha_compra',
				fieldLabel: 'Fecha Compra(factura)',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				format: 'd/m/Y',
				renderer:function (value,p,record){return value?value.dateFormat('d/m/Y H:i:s'):''}
			},
			type:'DateField',
			filters:{pfiltro:'predet.fecha_compra',type:'date'},
			id_grupo:1,
			grid:true,
			form:true
		},
		{
			config: {
				name: 'sw_generar',
				fieldLabel: 'Generar',
				anchor: '100%',
				tinit: false,
				allowBlank: true,
				origen: 'CATALOGO',
				gdisplayField: 'sw_generar',
				gwidth: 100,
				baseParams:{
					cod_subsistema:'PARAM',
					catalogo_tipo:'tgral__bandera_min'
				},
				renderer:function (value, p, record){return String.format('{0}', record.data['sw_generar']);}
			},
			type: 'ComboRec',
			id_grupo: 0,
			filters:{pfiltro:'predet.sw_generar',type:'string'},
			grid: false,
			form: false
		},
		{
			config:{
				name: 'observaciones',
				fieldLabel: 'Observaciones',
				allowBlank: true,
				anchor: '100%',
				gwidth: 200,
				maxLength:1000
			},
			type:'TextArea',
			filters:{pfiltro:'predet.observaciones',type:'string'},
			id_grupo:1,
			grid:true,
			form:true
		},
		{
			config : {
				name : 'id_ubicacion',
				fieldLabel : 'Local',
				allowBlank : false,
				emptyText : 'Local...',
				store : new Ext.data.JsonStore({
					url : '../../sis_kactivos_fijos/control/Ubicacion/listarUbicacion',
					id : 'id_ubicacion',
					root : 'datos',
					sortInfo : {
						field : 'nombre',
						direction : 'ASC'
					},
					totalProperty : 'total',
					fields : ['id_ubicacion', 'nombre', 'codigo'],
					remoteSort : true,
					baseParams : {
						par_filtro : 'ubi.nombre#ubi.codigo'
					}
				}),
				valueField : 'id_ubicacion',
				displayField : 'nombre',
				gdisplayField : 'desc_ubicacion',
				hiddenName : 'id_ubicacion',
				forceSelection : true,
				typeAhead : false,
				triggerAction : 'all',
				lazyRender : true,
				mode : 'remote',
				pageSize : 10,
				queryDelay : 1000,
				anchor : '100%',
				gwidth : 100,
				minChars : 2,
				renderer : function(value, p, record) {
					return String.format('{0}', record.data['desc_ubicacion']);
				}
			},
			type: 'ComboBox',
			id_grupo: 0,
			filters: {
				pfiltro : 'ubi.nombre#ubi.codigo',
				type : 'string'
			},
			grid : true,
			form : true
		},
		{
			config : {
				name : 'id_grupo',
				fieldLabel : 'Agrupador AE',
				allowBlank : false,
				emptyText : 'Agrupador...',
				store : new Ext.data.JsonStore({
					url : '../../sis_kactivos_fijos/control/Grupo/listarGrupo',
					id : 'id_grupo',
					root : 'datos',
					sortInfo : {
						field : 'nombre',
						direction : 'ASC'
					},
					totalProperty : 'total',
					fields : ['id_grupo', 'nombre', 'codigo'],
					remoteSort : true,
					baseParams : {
						par_filtro : 'gru.nombre#gru.codigo',
						tipo: 'grupo'
					}
				}),
				valueField : 'id_grupo',
				displayField : 'nombre',
				gdisplayField : 'desc_grupo',
				tpl:'<tpl for="."><div class="x-combo-list-item"><p>{codigo} - {nombre}</p> </div></tpl>',
				hiddenName : 'id_grupo',
				forceSelection : true,
				typeAhead : false,
				triggerAction : 'all',
				lazyRender : true,
				mode : 'remote',
				pageSize : 10,
				queryDelay : 1000,
				anchor : '100%',
				gwidth : 100,
				minChars : 2,
				renderer : function(value, p, record) {
					return String.format('{0}', record.data['desc_grupo']);
				}
			},
			type: 'ComboBox',
			id_grupo: 0,
			filters: {
				pfiltro : 'gru.nombre#gru.codigo',
				type : 'string'
			},
			grid : true,
			form : true
		},
		{
			config : {
				name : 'id_grupo_clasif',
				fieldLabel : 'Clasificador AE',
				allowBlank : false,
				emptyText : 'Clasificador...',
				store : new Ext.data.JsonStore({
					url : '../../sis_kactivos_fijos/control/Grupo/listarGrupo',
					id : 'id_grupo',
					root : 'datos',
					sortInfo : {
						field : 'nombre',
						direction : 'ASC'
					},
					totalProperty : 'total',
					fields : ['id_grupo', 'nombre', 'codigo'],
					remoteSort : true,
					baseParams : {
						par_filtro : 'gru1.nombre#gru1.codigo',
						tipo: 'clasificacion'
					}
				}),
				valueField : 'id_grupo',
				displayField : 'nombre',
				gdisplayField : 'desc_grupo_clasif',
				tpl:'<tpl for="."><div class="x-combo-list-item"><p>{codigo} - {nombre}</p> </div></tpl>',
				hiddenName : 'id_grupo_clasif',
				forceSelection : true,
				typeAhead : false,
				triggerAction : 'all',
				lazyRender : true,
				mode : 'remote',
				pageSize : 10,
				queryDelay : 1000,
				anchor : '100%',
				gwidth : 100,
				minChars : 2,
				renderer : function(value, p, record) {
					return String.format('{0}', record.data['desc_grupo_clasif']);
				}
			},
			type: 'ComboBox',
			id_grupo: 0,
			filters: {
				pfiltro : 'gru1.nombre#gru1.codigo',
				type : 'string'
			},
			grid : true,
			form : true
		},
		{
			config:{
				name: 'estado_reg',
				fieldLabel: 'Estado Reg.',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:10
			},
			type:'TextField',
			filters:{pfiltro:'predet.estado_reg',type:'string'},
			id_grupo:1,
			grid:true,
			form:false
		},
		{
			config:{
				name: 'nro_serie',
				fieldLabel: 'Nro.Serie',
				allowBlank: true,
				anchor: '100%',
				gwidth: 75,
				maxLength:50
			},
			type:'TextField',
			filters:{pfiltro:'predet.nro_serie',type:'string'},
			id_grupo:1,
			grid:true,
			form:true
		},
		{
			config:{
				name: 'marca',
				fieldLabel: 'Marca',
				allowBlank: true,
				anchor: '100%',
				gwidth: 75,
				maxLength:200
			},
			type:'TextField',
			filters:{pfiltro:'predet.marca',type:'string'},
			id_grupo:1,
			grid:true,
			form:true
		},
		{
			config:{
				name: 'vida_util',
				fieldLabel: 'Vida Útil (meses)',
				allowBlank: false,
				anchor: '80%',
				gwidth: 55,
				maxLength:200,
				disabled:true
			},
			type:'NumberField',
			filters:{pfiltro:'predet.vida_util',type:'numeric'},
			id_grupo:1,
			grid:true,
			form:true
		},
		{
			config:{
				name: 'usr_reg',
				fieldLabel: 'Creado por',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:4
			},
			type:'NumberField',
			filters:{pfiltro:'usu1.cuenta',type:'string'},
			id_grupo:1,
			grid:true,
			form:false
		},
		{
			config:{
				name: 'fecha_reg',
				fieldLabel: 'Fecha creación',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				format: 'd/m/Y',
				renderer:function (value,p,record){return value?value.dateFormat('d/m/Y H:i:s'):''}
			},
			type:'DateField',
			filters:{pfiltro:'predet.fecha_reg',type:'date'},
			id_grupo:1,
			grid:true,
			form:false
		},
		{
			config:{
				name: 'usr_mod',
				fieldLabel: 'Modificado por',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:4
			},
			type:'NumberField',
			filters:{pfiltro:'usu2.cuenta',type:'string'},
			id_grupo:1,
			grid:true,
			form:false
		},
		{
			config:{
				name: 'fecha_mod',
				fieldLabel: 'Fecha Modif.',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				format: 'd/m/Y',
				renderer:function (value,p,record){return value?value.dateFormat('d/m/Y H:i:s'):''}
			},
			type:'DateField',
			filters:{pfiltro:'predet.fecha_mod',type:'date'},
			id_grupo:1,
			grid:true,
			form:false
		}

	],
	tam_pag: 50,
	title: 'Preingreso',
	ActSave: '../../sis_almacenes/control/PreingresoDet/insertarPreingresoDetPreparacion',
	ActDel: '../../sis_almacenes/control/PreingresoDet/eliminarPreingresoDetPreparacion',
	ActList: '../../sis_almacenes/control/PreingresoDet/listarPreingresoDetV2',
	id_store: 'id_preingreso_det',
	fields: [
		{name:'id_preingreso_det', type: 'numeric'},
		{name:'estado_reg', type: 'string'},
		{name:'id_preingreso', type: 'numeric'},
		{name:'id_cotizacion_det', type: 'numeric'},
		{name:'id_item', type: 'numeric'},
		{name:'id_lugar', type: 'numeric'},
		{name:'id_almacen', type: 'numeric'},
		{name:'cantidad_det', type: 'numeric'},
		{name:'precio_compra', type: 'numeric'},
		{name:'precio_compra_87', type: 'numeric'},
		{name:'id_depto', type: 'numeric'},
		{name:'id_clasificacion', type: 'numeric'},
		{name:'sw_generar', type: 'string'},
		{name:'observaciones', type: 'string'},
		{name:'id_usuario_reg', type: 'numeric'},
		{name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'id_usuario_mod', type: 'numeric'},
		{name:'fecha_mod', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'fecha_conformidad', type: 'date',dateFormat:'Y-m-d'},
		{name:'fecha_compra', type: 'date',dateFormat:'Y-m-d'},
		{name:'c31', type: 'string'},
		{name:'usr_reg', type: 'string'},
		{name:'usr_mod', type: 'string'},
		{name:'desc_almacen', type: 'string'},
		{name:'desc_depto', type: 'string'},
		{name:'desc_item', type: 'string'},
		{name:'desc_clasificacion', type: 'string'},
		{name:'nombre', type: 'string'},
		{name:'nombre_lugar', type: 'string'},
		{name:'ubicacion', type: 'string'},
		{name:'descripcion', type: 'string'},
		{name:'estado', type: 'string'},
		{name:'tipo', type: 'string'},
		{name:'id_centro_costo', type: 'numeric'},
		{name:'id_ubicacion', type: 'numeric'},
		{name:'id_grupo', type: 'numeric'},
		{name:'id_grupo_clasif', type: 'numeric'},
		{name:'codigo_tcc', type: 'string'},
		{name:'desc_ubicacion', type: 'string'},
		{name:'desc_grupo', type: 'string'},
		{name:'desc_grupo_clasif', type: 'string'},
		{name:'nro_serie', type: 'string'},
		{name:'marca', type: 'string'},
		{name:'vida_util', type: 'numeric'}
	],
	sortInfo:{
		field: 'id_preingreso_det',
		direction: 'ASC'
	},
	bdel:false,
	bsave:false,
	bnew:true,
	bedit:true,
	preparaMenu: function(n){
       	Phx.vista.PreingresoDetModActV2.superclass.preparaMenu.call(this,n);
       	var data = this.getSelectedData();

       	this.getBoton('btnDesgl').disable()

       	if(data&&data.estado_reg=='activo'&&data.cantidad_det>1){
			this.getBoton('btnDesgl').enable()
       	}

		this.preparaComponentes(this.maestro)
	},

	loadValoresIniciales:function(){
		Phx.vista.PreingresoDetModActV2.superclass.loadValoresIniciales.call(this);
		this.getComponente('id_preingreso').setValue(this.maestro.id_preingreso);
		this.Cmp.fecha_conformidad.setValue(this.maestro.fecha_conformidad);
		this.Cmp.fecha_compra.setValue(this.maestro.fecha_conformidad);
		//this.Cmp.c31.setValue(this.Cmp.c31.getValue());
	},

	onReloadPage:function(m){
		this.maestro=m;

		Ext.apply(this.store.baseParams,{id_preingreso:this.maestro.id_preingreso,estado: this.estado});
		//this.preparaComponentes(this.maestro);
		this.load({params:{start:0, limit:this.tam_pag}});
	},
	onButtonEdit: function (){
		//Prepara los componentes en función de si el preingreso es para Almacén o para Activos Fijos
		Phx.vista.PreingresoDetModActV2.superclass.onButtonEdit.call(this);
		if (this.Cmp.fecha_conformidad.getValue() == '' || this.Cmp.fecha_conformidad.getValue() == undefined) {
			this.Cmp.fecha_conformidad.setValue(this.maestro.fecha_conformidad);
			this.Cmp.fecha_compra.setValue(this.maestro.fecha_conformidad);
		}

		/*if (this.Cmp.c31.getValue() == '' || this.Cmp.c31.getValue() == undefined) {
			this.Cmp.c31.setValue(this.Cmp.c31.getValue());
		}*/
		this.preparaComponentes(this.maestro)
	},
	preparaComponentes: function(pMaestro){
		var codSis;
		if(pMaestro.tipo=='activo_fijo'){
			//Setea store del departamento
			codSis='KAF';
			Ext.apply(this.Cmp.id_depto.store.baseParams,{codigo_subsistema:codSis});

			//Habilita componentes
			this.Cmp.precio_compra_87.enable();
            this.mostrarComponente(this.Cmp.precio_compra_87);
			this.Cmp.id_clasificacion.enable();
            this.mostrarComponente(this.Cmp.id_clasificacion);
            this.Cmp.id_depto.enable();
            this.mostrarComponente(this.Cmp.id_depto);
            this.Cmp.nombre.enable();
            this.mostrarComponente(this.Cmp.nombre);
            this.Cmp.descripcion.enable();
            this.mostrarComponente(this.Cmp.descripcion);
            this.Cmp.id_lugar.enable();
            this.mostrarComponente(this.Cmp.id_lugar);
            this.Cmp.ubicacion.enable();
            this.mostrarComponente(this.Cmp.ubicacion);
            //this.Cmp.c31.enable();
            //this.mostrarComponente(this.Cmp.c31);
            this.Cmp.fecha_conformidad.enable();
            this.mostrarComponente(this.Cmp.fecha_conformidad);
            this.mostrarComponente(this.Cmp.fecha_compra);
            this.Cmp.vida_util.enable();
            this.mostrarComponente(this.Cmp.vida_util);
            this.mostrarColumna(5);
            this.mostrarColumna(8);
            this.mostrarColumna(9);
            this.mostrarColumna(10);
            this.mostrarColumna(11);
            this.mostrarColumna(12);
            this.mostrarColumna(13);
            this.mostrarColumna(14);
            this.mostrarColumna(15);
            this.mostrarColumna(16);

			//Deshabilita componentes
			this.Cmp.id_almacen.disable();
            this.ocultarComponente(this.Cmp.id_almacen);
            this.Cmp.id_item.disable();
            this.ocultarComponente(this.Cmp.id_item);
            this.ocultarColumna(5);
            this.ocultarColumna(6);

		} else if(pMaestro.tipo=='almacen'){
			//Setea store del departamento
			codSis='ALM';
			Ext.apply(this.Cmp.id_depto.store.baseParams,{codigo_subsistema:codSis});

			//Habilita componentes
			this.Cmp.id_almacen.enable();
            this.mostrarComponente(this.Cmp.id_almacen);
            this.Cmp.id_item.enable();
            this.mostrarComponente(this.Cmp.id_item);
            this.mostrarColumna(5);
            this.mostrarColumna(6);

			//Deshabilita componentes
			this.Cmp.precio_compra_87.disable();
            this.ocultarComponente(this.Cmp.precio_compra_87);
			this.Cmp.id_clasificacion.disable();
            this.ocultarComponente(this.Cmp.id_clasificacion);
            this.Cmp.id_depto.disable();
            this.ocultarComponente(this.Cmp.id_depto);
            this.Cmp.nombre.disable();
            this.ocultarComponente(this.Cmp.nombre);
            this.Cmp.descripcion.disable();
            this.ocultarComponente(this.Cmp.descripcion);
            this.Cmp.id_lugar.disable();
            this.ocultarComponente(this.Cmp.id_lugar);
            this.Cmp.ubicacion.disable();
            this.ocultarComponente(this.Cmp.ubicacion);
            //this.Cmp.c31.disable();
            //this.ocultarComponente(this.Cmp.c31);
            this.ocultarComponente(this.Cmp.fecha_conformidad);
            this.ocultarComponente(this.Cmp.fecha_compra);
            this.Cmp.vida_util.disable();
            this.ocultarComponente(this.Cmp.vida_util);

            this.ocultarColumna(5);
            this.ocultarColumna(8);
            this.ocultarColumna(9);
            this.ocultarColumna(10);
            this.ocultarColumna(11);
            this.ocultarColumna(12);
            this.ocultarColumna(13);
            this.ocultarColumna(14);
            this.ocultarColumna(15);
            this.ocultarColumna(16);

        } else {
			//Setea store del departamento
			codSis='error';
			Ext.apply(this.Cmp.id_depto.store.baseParams,{codigo_subsistema:codSis});
			this.ocultarColumna(5);
			this.ocultarColumna(6);
            this.ocultarColumna(7);
			this.ocultarColumna(8);
		}

		//Habilita los componentes
		if(pMaestro.estado=='borrador'){
           this.getBoton('new').enable();
           this.getBoton('edit').enable();
           this.getBoton('btnAgTodos').enable();
      	} else{
       	   this.getBoton('new').disable();
           this.getBoton('edit').disable();
           this.getBoton('btnAgTodos').disable();
       	}
	},

	aplicarFiltro: function(){
		this.store.baseParams.estado=this.estado;
        this.load();
	},

	quitarTodos: function(){
		//Verifica si el grid tiene registros cargados
		if(this.store.getTotalCount()>0){
			Ext.Msg.show({
			   title:'Confirmación',
			   msg: '¿Está seguro de quitar todos los items del Preingreso?',
			   buttons: Ext.Msg.YESNO,
			   fn: function(a,b,c){
			   		if(a=='yes'){
			   			var myPanel = Phx.CP.getPagina(this.idContenedorPadre);
						Phx.CP.loadingShow();
						Ext.Ajax.request({
							url: '../../sis_almacenes/control/PreingresoDet/quitaPreingresoAll',
							params: {
								id_preingreso: this.maestro.id_preingreso
							},
							success: function(a,b,c){
								Phx.CP.loadingHide();
								this.reload();
								//Carga datos del panel derecho
								myPanel.onReloadPage(this.maestro);
								delete myPanel;
							},
							failure: this.conexionFailure,
							timeout: this.timeout,
							scope: this
						});
			   		}
			   },
			   icon: Ext.MessageBox.QUESTION,
			   scope: this
			});

		}
	},

	successDel:function(resp){
		Phx.CP.loadingHide();
		this.reload();

		//Recarga al padre
		var myPanel = Phx.CP.getPagina(this.idContenedorPadre);
		myPanel.onReloadPage(this.maestro);
		delete myPanel;
	},

	desglosar: function(){
		//Verifica si se seleccionó un registro activo
		var data = this.getSelectedData();

		if(data&&data.estado_reg=='activo'&&data.cantidad_det>1){
			Ext.Msg.show({
			   title:'Confirmación',
			   msg: '¿Está seguro de desglosar el registro en '+data.cantidad_det+'?',
			   buttons: Ext.Msg.YESNO,
			   fn: function(a,b,c){
			   		if(a=='yes'){
			   			var myPanel = Phx.CP.getPagina(this.idContenedorPadre);
						Phx.CP.loadingShow();
						Ext.Ajax.request({
							url: '../../sis_almacenes/control/PreingresoDet/desglosaRegistro',
							params: {
								id_preingreso_det: data.id_preingreso_det,
								cantidad_det: data.cantidad_det
							},
							success: function(a,b,c){
								Phx.CP.loadingHide();
								this.reload();
								//Carga datos del panel derecho
								myPanel.onReloadPage(this.maestro);
								delete myPanel;
							},
							failure: this.conexionFailure,
							timeout: this.timeout,
							scope: this
						});
			   		}
			   },
			   icon: Ext.MessageBox.QUESTION,
			   scope: this
			});
		}
	},

	oncellclick: function(grid, rowIndex, columnIndex, e) {

	    var record = this.store.getAt(rowIndex),
	        fieldName = grid.getColumnModel().getDataIndex(columnIndex); // Get field name

		if (fieldName == 'quitar') {

			var myPanel = Phx.CP.getPagina(this.idContenedorPadre);

			if(this.maestro.estado == 'finalizado'){
				Ext.Msg.alert('Acción no permitida','El preingreso ya fue finalizado, no puede hacerse ninguna modificación.');
			} else {
				Phx.CP.loadingShow();
				Ext.Ajax.request({
					url : '../../sis_almacenes/control/PreingresoDet/eliminarPreingresoDetPreparacion',
					params : {
						id_preingreso_det:	record.data.id_preingreso_det,
						data: record
					},
					success : function(a,b,c){
						Phx.CP.loadingHide();
						this.reload();
						//Carga datos del panel derecho
						myPanel.onReloadPage(this.maestro);
						delete myPanel;
					},
					failure : this.conexionFailure,
					timeout : this.timeout,
					scope : this
				});
			}

	    }

	}

})
</script>