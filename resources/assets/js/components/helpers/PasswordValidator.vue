<template>
    <div class="form-group">
        <input type="password" name="contrasena" class="form-control" placeholder="Ingrese una contraseña" v-model="password" required>
        <small id="passwordHelp" required class="form-text text-muted" :hidden="complete">La contraseña debe contener
            <span :class="has_minimum_lenth ? 'has_required' : ''">Al menos 8 caracteres, </span>
            <span :class="has_lowercase ? 'has_required' : ''">Una minuscula, </span>
            <span :class="has_uppercase ? 'has_required' : ''">Una mayucula, </span>
            <span :class="has_number ? 'has_required' : ''">Un número, </span>
            <span :class="has_special ? 'has_required' : ''">Un caracter especial.</span>
        </small>
        <!--

            <small>
                <span :class="has_special ? 'has_required' : ''">Un caracter especial.</span>
            </small>
        -->
    </div>
</template>

<script>
export default {
    data() {
        return {
            password: '',
            has_minimum_lenth: false,
            has_number: false,
            has_lowercase: false,
            has_uppercase: false,
            has_special: false,
            complete: false,
        }
    },
    watch:{
        password(){
            this.has_minimum_lenth = (this.password.length > 8);
            this.has_number    = /\d/.test(this.password);
            this.has_lowercase = /[a-z]/.test(this.password);
            this.has_uppercase = /[A-Z]/.test(this.password);
            this.has_special   = /[!@#\$%\^\&*\)\(+=._-]/.test(this.password);
            this.complete = /^(?=.*[0-9])(?=.*[- ?!@#$%^&*\/\\])(?=.*[A-Z])(?=.*[a-z])[a-zA-Z0-9- ?!@#$%^&*\/\\]{8,30}$/.test(this.password);
        }

    }
};

</script>
<style>
.has_required{
    display: none;
}
</style>