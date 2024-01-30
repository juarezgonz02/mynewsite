import {API_HOST} from '../../constants/endpoint.js';
import {API_HOST_ASSETS} from '../../constants/endpoint.js';
import Swal from 'sweetalert2';

export default {
    data(){
        return{
            ruta : API_HOST_ASSETS,
            loading : 0,
            loadTable : false,
            user_email: '',
            arrayProyectos : [],
            arrayCarreras : [''],
            arrayCarrerasSin : [''],
            arrayCarrerasCon : [''],
            arrayPerfiles : [''],
            arrayCarreraPerfil : [[]],
            arrayEstudiantes : [],
            nombre_estudiante_msg : '',
            add_edit_flag : 0,
            modal : 0,
            modal2 : 0,
            modal3 : 0,
            modal4 : 0,
            modal5 : 0,
            modal6 : 0,
            id_proyecto : 0,  
            id_estudiante : 0,              
            modal_encargado : '',
            modal_nombre : '',
            modal_desc : '',
            modal_correo : '',
            modal_tipo_horas : '',
            modal_cupos_act : 0,
            modal_cupos : '',
            modal_horario : '',
            modal_fecha_in : '',
            modal_fecha_fin : '',
            modal_contraparte : '',
            modal_carrera : 0,
            modal_perfil : 0,
            modal_createdAt : '',
            modal_confirmar : '',
            modal_descripcion : '',
            modal_fecha : '',
            modal_hora : '',
            modal_lugar : '',
            carnet : '',
            nombre_completo : '',
            errorProyecto : [''],
            errorDateMsg : '',
            errorPerfilMsg : '',
            errorEstudianteMsg : '',
            errorEstado : 0,
            flagError : false,
            flagErrorProyecto : false,
            flagEstudiante : false,
            flagErrorEstado : false,
            modal5 : 0,
            modal_user_carnet : "",
            modal_user_name : "",
            modal_user_carrera : "",
            modal_user_año : "",
            pagination : {
                'total' : 0,
                'current_page' : 0,
                'per_page' : 0,
                'last_page' : 0,
                'from' : 0,
                'to' : 0
            },
            offset : 3,
            arraySolicitudes: [],
            filtrandoPorNombre: "",
            filtrandoPorCarrera: JSON.stringify({'por': 'carrera', 'id': -1}),
            ordenandoPor: "recientes",
            selectedFilter: "",
            proyecto: "",
            arrayFactultad: []
        }
    },
    computed:{
        isActived : function(){
            return this.pagination.current_page;
        },
        pagesNumber : function(){
            if(!this.pagination.to){
                return [];
            }
            var from = this.pagination.current_page - this.offset;
            if (from < 1) {
                from = 1;
            }
            var to = from + (this.offset * 2);
            if(to >= this.pagination.last_page){
                to = this.pagination.last_page;
            }
            var pagesArray = []
            while(from <= to){
                pagesArray.push(from);
                from++;
            }
            return pagesArray;
        }
    },
    methods:{
        bindData(page){

            let me = this;
            me.loadTable = true;

            var url = `${API_HOST}/todos_proyectos?page=${page}`;
            me.getFacultadesCarrerasAndPerfils();
            axios.get(`${API_HOST}/get_user`).then(function (response) {
                me.user_email = response.data.correo;
            })
            .catch(function (error) {
                console.log(error);
            });
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayProyectos = respuesta.proyectos.data;
                me.pagination = respuesta.pagination;
                me.loadTable = false;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        bindDataByFilters(page){

            let me = this;

            me.loadTable = true;
            
            let filtros = JSON.parse(me.filtrandoPorCarrera)

            var url = `${API_HOST}/solicitudes?nombre=${me.filtrandoPorNombre}&filtro=${filtros.por}&id=${filtros.id}&orden=${me.ordenandoPor}&page=${page}`;

            me.getFacultadesCarrerasAndPerfils();
            
            axios.get(`${API_HOST}/get_user`).then(function (response) {
                me.user_email = response.data.correo;
            })
            .catch(function (error) {
                console.log(error);
            });
            
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arraySolicitudes = respuesta.solicitudes.data;
                me.pagination = respuesta.pagination;
                me.loadTable = false;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        cambiarPagina(page){
            let me = this;
            me.pagination.current_page = page;

            me.bindDataByFilters(page);
            
        },
        actualizarInsertarProyecto(){
            if(this.validarProyecto()){
                return;
            }
            let me = this;
            if(!this.id_proyecto){
                axios.post(`${API_HOST}/proyecto/insertar`, {
                    'idProyecto' : this.id_proyecto,
                    'nombre' : this.modal_nombre,
                    'estado' : 1,
                    'contraparte' : this.modal_contraparte,
                    'cupos_act' : 0,
                    'cupos' : this.modal_cupos,
                    'descripcion' : this.modal_desc,
                    'encargado' : this.modal_encargado,
                    'fecha_inicio' : this.modal_fecha_in,
                    'fecha_fin' : this.modal_fecha_fin,
                    'horario' : this.modal_horario,
                    'tipo_horas' : this.modal_tipo_horas,
                    'correo_encargado' : this.modal_correo,
                    'carreraPerfil' : this.arrayCarreraPerfil
                }).then(function (response) {
                    me.cerrarModal();
                    me.bindData();
                }).catch(function (error) {
                    console.log(error);
                }); 
            }
            else{
                axios.put(`${API_HOST}/proyecto/actualizar`, {
                    'idProyecto' : this.id_proyecto,
                    'nombre' : this.modal_nombre,
                    'contraparte' : this.modal_contraparte,
                    'cupos' : this.modal_cupos,
                    'descripcion' : this.modal_desc,
                    'encargado' : this.modal_encargado,
                    'fecha_inicio' : this.modal_fecha_in,
                    'fecha_fin' : this.modal_fecha_fin,
                    'horario' : this.modal_horario,
                    'tipo_horas' : this.modal_tipo_horas,
                    'correo_encargado' : this.modal_correo,
                    'carreraPerfil' : this.arrayCarreraPerfil
                }).then(function (response) {
                    me.cerrarModal();
                    me.bindData();
                }).catch(function (error) {
                    console.log(error);
                }); 
            }
        },
        enviarReunion(){
            if(this.validarReunion()){
                return;
            }
            let me = this;
            if(this.id_proyecto){
                axios.post(`${API_HOST}/sendMeetingMail`, {
                    'nombre_proyecto' : this.proyecto,
                    'descripcion' : this.modal_descripcion,
                    'encargado' : this.modal_encargado,
                    "encargado_correo": this.modal_correo,
                    'estudiantes' : this.arrayEstudiantes.map(e => e.correoCompleto),
                    'lugar' : this.modal_lugar ,
                    'fecha' : this.modal_fecha ,
                    'hora' : this.modal_hora 
                }).then(function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Correos enviados',
                        text: 'Los correos con información de la reunión fue enviado exitosamente.',
                    });
                    me.cerrarModal();
                }).catch(function (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Correos no enviados',
                        text: 'Los correos con la información de la reunión no fue enviado. Intente nuevamente.',
                    });
                    console.log(error);
                });
            }
        },
        regexCorreo(correo){
            let re = new RegExp("^[a-zA-Z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$");
            return re.test(correo)
        },
        validarProyecto(){
            this.errorProyecto = [];
            this.flagError = false;
            this.errorPerfilMsg = "";

            if(!this.modal_nombre) this.errorProyecto.push(1);
            else this.errorProyecto.push(0);
            if(!this.modal_encargado) this.errorProyecto.push(1);
            else this.errorProyecto.push(0);
            if(!this.modal_cupos) this.errorProyecto.push(1);
            else if(this.modal_cupos < 1) this.errorProyecto.push(2);
            else this.errorProyecto.push(0);
            if(!this.modal_tipo_horas) this.errorProyecto.push(1);
            else this.errorProyecto.push(0)
            if(!this.modal_desc) this.errorProyecto.push(1);
            else if(this.modal_desc.length > 2000) this.errorProyecto.push(2);
            else this.errorProyecto.push(0)
            if(!this.modal_correo) this.errorProyecto.push(1)
            else if(!this.regexCorreo(this.modal_correo)) this.errorProyecto.push(2)
            else this.errorProyecto.push(0)
            if(!this.modal_horario) this.errorProyecto.push(1);
            else this.errorProyecto.push(0);
            if(!this.modal_contraparte) this.errorProyecto.push(1);
            else this.errorProyecto.push(0);
            if(!this.modal_fecha_in) {
                this.errorProyecto.push(1);
                this.errorDateMsg = "Debe seleccionar una fecha";
            }
            else if(this.modal_fecha_in >= this.modal_fecha_fin) {
                this.errorProyecto.push(2);
                this.errorDateMsg = "La fecha seleccionada no concuerda con la otra fecha"
            }
            else this.errorProyecto.push(0);
            if(!this.modal_fecha_fin) this.errorProyecto.push(1);
            else if(this.modal_fecha_in >= this.modal_fecha_fin) this.errorProyecto.push(2);
            else this.errorProyecto.push(0);
            
            var flagCP1 = true, flagCP2 = true, flagCP3 = true;
            var msg1 = "", msg2 = "", msg3 = "", msg4 = "";
            var i = 0, j = 0;

            if(this.arrayCarreraPerfil.length == 0){
                this.flagError = true
                msg4 = "Debe agregar carreras"
            }
            
            this.arrayCarreraPerfil.forEach(document => {
                if((!document[0] || !document[1] || !document[2]) && flagCP1){
                    msg1 = "Debe seleccionar todos los campos. ";
                    flagCP1 = false;
                    this.flagError = true;
                }
                if(document[1] > document[2] && flagCP2){
                    msg2 = "Rango invalido, seleccione rangos válidos. ";
                    flagCP2 = false;
                    this.flagError = true;
                }
                j = 0;
                this.arrayCarreraPerfil.forEach(subDocument => {
                    if(subDocument[0] && (i != j && document[0] == subDocument[0]) && flagCP3){
                        msg3 = "No puede seleccionar la misma carrera más de una vez."
                        flagCP3 = false;
                        this.flagError = true;
                    }
                    j++
                })
                i++;
            })
            this.errorPerfilMsg += msg1 + msg2 + msg3 + msg4;
            var tempFlag = false
            if(this.errorProyecto.find(element => element > 0) == undefined){
                tempFlag = true
            }
            if(tempFlag && !this.flagError){
                //No hay errores
                this.flagErrorProyecto = false
                return false;
            } 
            this.flagErrorProyecto = true
            return true;
        },
        validarReunion(){
            this.errorProyecto = [];
            this.flagError = false;
            this.errorPerfilMsg = "";

            if(!this.modal_fecha) {
                this.errorProyecto.push(1);
                this.errorDateMsg = "Debe seleccionar una fecha";
            }
            else this.errorProyecto.push(0);
            if(!this.modal_hora) this.errorProyecto.push(1);
            else this.errorProyecto.push(0);
            if(!this.modal_lugar) this.errorProyecto.push(1);
            else this.errorProyecto.push(0);

            if(this.arrayEstudiantes.length == 0){
                this.flagError = true
                this.errorPerfilMsg = "El proyecto no tiene estudiantes inscritos"
            }
            
            var tempFlag = false
            if(this.errorProyecto.find(element => element > 0) == undefined){
                tempFlag = true
            }
            if(tempFlag && !this.flagError){
                //No hay errores
                this.flagErrorProyecto = false
                return false;
            }
            this.flagErrorProyecto = true;
            return true;
        },
        estadoProyecto(){
            let me = this;
            if(me.modal_confirmar != me.modal_nombre){
                me.flagErrorEstado = true
                me.errorEstado = 1
            }
            else {
                me.loading = 1
                axios.put(`${API_HOST}/proyecto/estado`, {
                    'idProyecto' : this.id_proyecto,
                    'estado' : 0
                }).then(function (response) {
                    $('#statusModal').modal('hide');
                    me.loading = 2;
                    me.bindData();
                    me.cerrarModal();
                }).catch(function (error) {
                    console.log(error);
                }); 
            }
        },
        cerrarModal(){
            if(this.modal == 1 || this.modal4 == 1){
                this.modal = 0;
                this.modal4 = 0;
            }
            else{
                this.add_edit_flag = 0;
                this.modal2 = 0;
                this.modal3 = 0;
                this.modal5 = 0;
                this.modal6 = 0;
                this.id_proyecto = 0;
            }
        },
        abrirModal(modelo, data = [], flag){
            this.loading = 0
            switch (modelo) {
                case "insertar":
                    {
                        this.modal = 1;
                        this.add_edit_flag = 1;
                        this.arrayCarreraPerfil = [[]];
                        this.modal_nombre = '';
                        this.modal_encargado = '';
                        this.modal_cupos = ''
                        this.modal_desc = '';
                        this.modal_correo = '';
                        this.modal_horario = '';
                        this.modal_contraparte = '';
                        this.modal_tipo_horas = '';
                        this.contraparte = '';
                        this.modal_fecha_in = '';
                        this.modal_fecha_fin = '';
                        this.errorProyecto = [];
                        this.errorPerfilMsg = '';
                        this.flagError = false;
                        this.flagErrorProyecto = false;
                        this.arrayCarreraPerfil = [[]];
                        break;
                    }
                case "editar":
                    {
                        this.modal = 1;
                        this.add_edit_flag = 2;
                        this.id_proyecto = data.idProyecto;
                        this.modal_encargado = data.encargado;
                        this.modal_nombre = data.nombre;
                        this.modal_desc = data.descripcion;
                        this.modal_correo = data.correo_encargado;
                        this.modal_tipo_horas = data.tipo_horas;
                        this.modal_cupos = data.cupos;
                        this.modal_horario = data.horario;
                        this.modal_fecha_in = data.fecha_inicio;
                        this.modal_fecha_fin = data.fecha_fin;
                        this.modal_contraparte = data.contraparte;
                        this.modal_createdAt = data.createdAt;
                        this.flagError = false;
                        this.flagErrorProyecto = false;
                        this.errorPerfilMsg = "";
                        this.arrayCarreraPerfil = [[]];
                        this.updateCarrerasAndPerfil();
                        break;
                    }
                case "estado":
                    {
                        this.modal2 = 1;
                        this.id_proyecto = data.idProyecto;
                        this.modal_nombre = data.nombre;
                        this.errorEstado = 0;
                        this.modal_confirmar = '';
                        this.flagErrorEstado = false;
                        break;
                    }
                case "estudiantes":
                    {
                        this.modal3 = 1;
                        this.id_proyecto = data.idProyecto;
                        this.modal_nombre = data.nombre;
                        this.modal_cupos = data.cupos;
                        this.carnet = '';
                        this.nombre_completo = '';
                        this.id_estudiante = 0;
                        this.flagError = false;
                        this.errorEstudianteMsg = '';
                        this.getEstudiantes()
                        break;
                    }
                case "confirmacion":
                    {
                        this.modal4 = 1;
                        if(flag) this.nombre_estudiante_msg = "¿Aceptar al estudiante " + data.nombres + " " + data.apellidos + "?";
                        else this.nombre_estudiante_msg = "¿Rechazar al estudiante " + data.nombres + " " + data.apellidos + "?";
                        this.flagEstudiante = flag;
                        this.id_estudiante = data.idUser;
                        break;
                    }
                case "info_user":
                    {
                        this.modal5 = 1;
                        this.modal_user_carnet = data.correo;
                        this.modal_user_name = `${data.u_nombre} ${data.u_apellido}`;
                        this.modal_user_carrera = data.carrera;
                        this.modal_user_año = data.ano;
                    }
                    break;
                case "info_proyecto":
                    {
                        this.modal5 = 1;
                        this.id_proyecto = data.idProyecto;
                        this.modal_encargado = data.encargado;
                        this.modal_nombre = data.nombre;
                        this.modal_desc = data.descripcion;
                        this.modal_tipo_horas = data.tipo_horas;
                        this.modal_cupos_act = data.cupos_act;
                        this.modal_cupos = data.cupos;
                        this.modal_horario = data.horario;
                        this.modal_fecha_in = data.fecha_inicio;
                        this.modal_fecha_fin = data.fecha_fin;
                    }
                default:
                    break;
            }
        },
        getFacultadesCarrerasAndPerfils(){
            let me = this
            axios.get(`${API_HOST}/carrera`).then(function (response) {
                me.arrayCarrerasSin = response.data;
                me.arrayCarreras = me.arrayCarrerasSin.slice();
                me.arrayCarreras.push({idCarrera : -1, idFacultad : -1, nombre : "Todas las carreras"});
                me.arrayCarreras.push({idCarrera : -2, idFacultad : -2, nombre : "Todas las carreras menos Psicología, Civil y Arquitectura"});
                me.arrayCarrerasCon = me.arrayCarreras.slice();
            })
            .catch(function (error) {
                console.log(error);
            });

            axios.get(`${API_HOST}/perfil`).then(function (response) {
                me.arrayPerfiles = response.data;
            })
            .catch(function (error) {
                console.log(error);
            });

            axios.get(`${API_HOST}/facultad`).then(function (response) {
                me.arrayFactultad = response.data;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        updateCarrerasAndPerfil(){
            let me = this
            axios.get(`${API_HOST}/proyectosxcarrera`, {
                params:{
                    idProyecto: me.id_proyecto
                }
            }).then(function(response){
                var i = 0;
                response.data.forEach(document =>{
                    me.arrayCarreraPerfil[i][0] = document.idCarrera;
                    me.arrayCarreraPerfil[i][1] = document.limite_inf;
                    me.arrayCarreraPerfil[i][2] = document.limite_sup;
                    me.arrayCarreraPerfil.push([]);
                    i++;
                })
                if(i != 0) me.arrayCarreraPerfil.pop() 
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        agregarACP(){
            this.arrayCarreraPerfil.push([])
        },
        eliminarACP(acp){
            let index = this.arrayCarreraPerfil.indexOf(acp);
            this.arrayCarreraPerfil.splice(index, 1)
        },
        getEstudiantes(){
            let me = this;
            axios.get(`${API_HOST}/estudiantesxproyecto`, {
                params:{
                    idProyecto: me.id_proyecto
                }
            }).then(function (response){
                me.arrayEstudiantes = response.data;
                me.arrayEstudiantes.forEach(function(element, index, array){
                    me.arrayEstudiantes[index].correoCompleto = element.correo;
                    me.arrayEstudiantes[index].correo = element.correo.substr(0, 8);
                    me.arrayEstudiantes[index].nombreCompleto = element.nombres + " " + element.apellidos;
                })
            })
            .catch(function (error) {
                console.log(error);
            });
            axios.get(`${API_HOST}/cupos_actuales`, {
                params:{
                    idProyecto: me.id_proyecto
                }
            }).then(function (response){
                me.modal_cupos_act = response.data.cupos - response.data.cupos_act;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        buscarEstudiante(){
            let me = this
            //this.errorActualizar = false
            var url = `${API_HOST}/estudiante_por_carnet`
            axios.get(url, {
                params:{
                    carnet: me.carnet
                }
            }).then(function (response) {
                var estudiante = response.data[0];
                if(estudiante != null){
                    me.nombre_completo = estudiante.nombres + " " + estudiante.apellidos;
                    me.id_estudiante = estudiante.idUser;
                    me.flagError = false;
                }
                else{
                    me.errorEstudianteMsg = "No se ha encontrado resultados";
                    me.flagError = true;
                }
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        aplicarPorAdmin(){
            let me = this
            me.loading = 1;
            var url = `${API_HOST}/aplicarporadmin`
            axios.post(url, {
                'idProyecto' : me.id_proyecto,
                'idUser' : me.id_estudiante,
                'estado' : 1,
            }).then(function (response) {
                if(response.data){
                    me.errorEstudianteMsg = response.data;
                    me.flagError = true;
                }
                me.loading = 0;
                me.getEstudiantes();
                me.bindData();
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        aceptarRechazarEstudiante(){
            let me = this;
            me.loading = 1;
            var estadoEst = 2;
            if(me.flagEstudiante){
                axios.put(`${API_HOST}/rechazarestudiante`, {
                    'idUser' : me.id_estudiante,
                    'idProyecto' : me.id_proyecto,
                }).catch(function (error) {
                    console.log(error);
                }); 
                estadoEst = 1;
            }
            axios.put(`${API_HOST}/aplicarestudiante`, {
                'idUser' : me.id_estudiante,
                'idProyecto' : me.id_proyecto,
                'estado' : estadoEst
            }).then(function (response) {
                $('#confirmModal').modal('hide');
                me.loading = 2;
                me.cerrarModal();
                me.getEstudiantes();
                me.bindData();
                me.loading = 0;
            }).catch(function (error) {
                console.log(error);
            }); 
        },
        logout(){
            var url = `${API_HOST}/logout`;
            axios.post(url).then(() => location.href = `${API_HOST}/`)
        }
    },
    watch:{
        arrayCarreraPerfil:function(val){paginate
            if(this.arrayCarreraPerfil.length > 0){
                if(this.arrayCarreraPerfil[this.arrayCarreraPerfil.length-1][0]){
                    if(this.arrayCarreraPerfil[0][0] == -1 || this.arrayCarreraPerfil[0][0] == -2){
                        document.getElementById("agregarCP").disabled = true;
                    }
                    else{
                        document.getElementById("agregarCP").disabled = false;
                        this.arrayCarreras = this.arrayCarrerasSin.slice();
                    }
                }
                else{
                    document.getElementById("agregarCP").disabled = true;
                }
            }
            else{
                document.getElementById("agregarCP").disabled = false;
                this.arrayCarreras = this.arrayCarrerasCon.slice();
            }
        },
        flagErrorEstado:function(){
            console.log("Hola")
        }
    },
    mounted() {
        this.bindDataByFilters(1);
    },
}