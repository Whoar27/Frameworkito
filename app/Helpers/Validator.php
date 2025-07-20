<?php
/**
 * Validator - Validador de Datos
 * Frameworkito
 * 
 * Sistema de validación flexible con:
 * - Reglas predefinidas
 * - Mensajes personalizables
 * - Validaciones anidadas
 * - Soporte para arrays
 */

namespace App\Helpers;

class Validator {
    private array $data = [];
    private array $rules = [];
    private array $messages = [];
    private array $errors = [];
    private array $customMessages = [];

    /**
     * Mensajes de error por defecto
     */
    private array $defaultMessages = [
        'required' => 'El campo :field es obligatorio.',
        'email' => 'El campo :field debe ser un email válido.',
        'min' => 'El campo :field debe tener al menos :min caracteres.',
        'max' => 'El campo :field no debe exceder :max caracteres.',
        'numeric' => 'El campo :field debe ser numérico.',
        'integer' => 'El campo :field debe ser un número entero.',
        'alpha' => 'El campo :field solo debe contener letras.',
        'alpha_num' => 'El campo :field solo debe contener letras y números.',
        'url' => 'El campo :field debe ser una URL válida.',
        'date' => 'El campo :field debe ser una fecha válida.',
        'confirmed' => 'La confirmación de :field no coincide.',
        'same' => 'El campo :field debe ser igual a :other.',
        'different' => 'El campo :field debe ser diferente de :other.',
        'in' => 'El campo :field debe ser uno de: :values.',
        'not_in' => 'El campo :field no debe ser uno de: :values.',
        'unique' => 'El campo :field ya está en uso.',
        'exists' => 'El campo :field seleccionado no es válido.',
        'regex' => 'El formato del campo :field no es válido.',
        'file' => 'El campo :field debe ser un archivo.',
        'image' => 'El campo :field debe ser una imagen.',
        'mimes' => 'El campo :field debe ser un archivo de tipo: :mimes.',
        'size' => 'El campo :field debe tener exactamente :size caracteres.',
        'between' => 'El campo :field debe estar entre :min y :max.',
        'boolean' => 'El campo :field debe ser verdadero o falso.',
        'array' => 'El campo :field debe ser un array.',
        'phone' => 'El campo :field debe ser un número de teléfono válido.',
        'ip' => 'El campo :field debe ser una dirección IP válida.',
        'json' => 'El campo :field debe ser una cadena JSON válida.'
    ];

    public function __construct(array $data = [], array $rules = [], array $messages = []) {
        $this->data = $data;
        $this->rules = $rules;
        $this->customMessages = $messages;
    }

    /**
     * Crear instancia del validador
     */
    public static function make(array $data, array $rules, array $messages = []): self {
        return new self($data, $rules, $messages);
    }

    /**
     * Validar datos
     */
    public function validate(): bool {
        $this->errors = [];

        foreach ($this->rules as $field => $rules) {
            $this->validateField($field, $rules);
        }

        return empty($this->errors);
    }

    /**
     * Validar campo específico
     */
    private function validateField(string $field, $rules): void {
        if (is_string($rules)) {
            $rules = explode('|', $rules);
        }

        $value = $this->getValue($field);
        $isRequired = in_array('required', $rules);

        // Si el campo no es requerido y está vacío, no validar otras reglas
        if (!$isRequired && $this->isEmpty($value)) {
            return;
        }

        foreach ($rules as $rule) {
            if (!$this->validateRule($field, $value, $rule)) {
                break; // Parar en la primera regla que falle
            }
        }
    }

    /**
     * Validar regla específica
     */
    private function validateRule(string $field, $value, string $rule): bool {
        // Parsear regla con parámetros
        $parameters = [];
        if (strpos($rule, ':') !== false) {
            [$rule, $parameterString] = explode(':', $rule, 2);
            $parameters = explode(',', $parameterString);
        }

        $method = 'validate' . ucfirst($rule);

        if (method_exists($this, $method)) {
            if (!$this->$method($field, $value, $parameters)) {
                $this->addError($field, $rule, $parameters);
                return false;
            }
        } else {
            throw new InvalidArgumentException("Regla de validación no soportada: {$rule}");
        }

        return true;
    }

    /**
     * =============================================================================
     * REGLAS DE VALIDACIÓN
     * =============================================================================
     */

    /**
     * Validar campo requerido
     */
    protected function validateRequired(string $field, $value, array $parameters): bool {
        return !$this->isEmpty($value);
    }

    /**
     * Validar email
     */
    protected function validateEmail(string $field, $value, array $parameters): bool {
        if ($this->isEmpty($value)) return true;
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Validar longitud mínima
     */
    protected function validateMin(string $field, $value, array $parameters): bool {
        if ($this->isEmpty($value)) return true;
        $min = (int) $parameters[0];

        if (is_array($value)) {
            return count($value) >= $min;
        }

        return mb_strlen((string) $value) >= $min;
    }

    /**
     * Validar longitud máxima
     */
    protected function validateMax(string $field, $value, array $parameters): bool {
        if ($this->isEmpty($value)) return true;
        $max = (int) $parameters[0];

        if (is_array($value)) {
            return count($value) <= $max;
        }

        return mb_strlen((string) $value) <= $max;
    }

    /**
     * Validar que sea numérico
     */
    protected function validateNumeric(string $field, $value, array $parameters): bool {
        if ($this->isEmpty($value)) return true;
        return is_numeric($value);
    }

    /**
     * Validar que sea entero
     */
    protected function validateInteger(string $field, $value, array $parameters): bool {
        if ($this->isEmpty($value)) return true;
        return filter_var($value, FILTER_VALIDATE_INT) !== false;
    }

    /**
     * Validar solo letras
     */
    protected function validateAlpha(string $field, $value, array $parameters): bool {
        if ($this->isEmpty($value)) return true;
        return ctype_alpha((string) $value);
    }

    /**
     * Validar letras y números
     */
    protected function validateAlphaNum(string $field, $value, array $parameters): bool {
        if ($this->isEmpty($value)) return true;
        return ctype_alnum((string) $value);
    }

    /**
     * Validar URL
     */
    protected function validateUrl(string $field, $value, array $parameters): bool {
        if ($this->isEmpty($value)) return true;
        return filter_var($value, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * Validar fecha
     */
    protected function validateDate(string $field, $value, array $parameters): bool {
        if ($this->isEmpty($value)) return true;
        $format = $parameters[0] ?? 'Y-m-d';
        $date = DateTime::createFromFormat($format, $value);
        return $date && $date->format($format) === $value;
    }

    /**
     * Validar confirmación de campo
     */
    protected function validateConfirmed(string $field, $value, array $parameters): bool {
        $confirmationField = $field . '_confirmation';
        $confirmationValue = $this->getValue($confirmationField);
        return $value === $confirmationValue;
    }

    /**
     * Validar que sea igual a otro campo
     */
    protected function validateSame(string $field, $value, array $parameters): bool {
        $otherField = $parameters[0];
        $otherValue = $this->getValue($otherField);
        return $value === $otherValue;
    }

    /**
     * Validar que sea diferente de otro campo
     */
    protected function validateDifferent(string $field, $value, array $parameters): bool {
        $otherField = $parameters[0];
        $otherValue = $this->getValue($otherField);
        return $value !== $otherValue;
    }

    /**
     * Validar que esté en lista de valores
     */
    protected function validateIn(string $field, $value, array $parameters): bool {
        if ($this->isEmpty($value)) return true;
        return in_array($value, $parameters);
    }

    /**
     * Validar que NO esté en lista de valores
     */
    protected function validateNotIn(string $field, $value, array $parameters): bool {
        if ($this->isEmpty($value)) return true;
        return !in_array($value, $parameters);
    }

    /**
     * Validar expresión regular
     */
    protected function validateRegex(string $field, $value, array $parameters): bool {
        if ($this->isEmpty($value)) return true;
        $pattern = $parameters[0];
        return preg_match($pattern, (string) $value) === 1;
    }

    /**
     * Validar tamaño exacto
     */
    protected function validateSize(string $field, $value, array $parameters): bool {
        if ($this->isEmpty($value)) return true;
        $size = (int) $parameters[0];

        if (is_array($value)) {
            return count($value) === $size;
        }

        return mb_strlen((string) $value) === $size;
    }

    /**
     * Validar rango
     */
    protected function validateBetween(string $field, $value, array $parameters): bool {
        if ($this->isEmpty($value)) return true;
        $min = (int) $parameters[0];
        $max = (int) $parameters[1];

        if (is_numeric($value)) {
            return $value >= $min && $value <= $max;
        }

        if (is_array($value)) {
            $count = count($value);
            return $count >= $min && $count <= $max;
        }

        $length = mb_strlen((string) $value);
        return $length >= $min && $length <= $max;
    }

    /**
     * Validar booleano
     */
    protected function validateBoolean(string $field, $value, array $parameters): bool {
        if ($this->isEmpty($value)) return true;
        return in_array($value, [true, false, 0, 1, '0', '1', 'true', 'false'], true);
    }

    /**
     * Validar array
     */
    protected function validateArray(string $field, $value, array $parameters): bool {
        return is_array($value);
    }

    /**
     * Validar teléfono
     */
    protected function validatePhone(string $field, $value, array $parameters): bool {
        if ($this->isEmpty($value)) return true;
        $pattern = '/^[\+]?[1-9][\d]{0,15}$/';
        $cleanPhone = preg_replace('/[^\d\+]/', '', $value);
        return preg_match($pattern, $cleanPhone);
    }

    /**
     * Validar IP
     */
    protected function validateIp(string $field, $value, array $parameters): bool {
        if ($this->isEmpty($value)) return true;
        return filter_var($value, FILTER_VALIDATE_IP) !== false;
    }

    /**
     * Validar JSON
     */
    protected function validateJson(string $field, $value, array $parameters): bool {
        if ($this->isEmpty($value)) return true;
        json_decode($value);
        return json_last_error() === JSON_ERROR_NONE;
    }

    /**
     * Validar archivo
     */
    protected function validateFile(string $field, $value, array $parameters): bool {
        return isset($_FILES[$field]) && $_FILES[$field]['error'] === UPLOAD_ERR_OK;
    }

    /**
     * Validar imagen
     */
    protected function validateImage(string $field, $value, array $parameters): bool {
        if (!$this->validateFile($field, $value, $parameters)) {
            return false;
        }

        $imageTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/bmp', 'image/webp'];
        return in_array($_FILES[$field]['type'], $imageTypes);
    }

    /**
     * Validar tipos MIME
     */
    protected function validateMimes(string $field, $value, array $parameters): bool {
        if (!$this->validateFile($field, $value, $parameters)) {
            return false;
        }

        $allowedMimes = $parameters;
        $fileMime = $_FILES[$field]['type'];

        return in_array($fileMime, $allowedMimes);
    }

    /**
     * =============================================================================
     * MÉTODOS DE UTILIDAD
     * =============================================================================
     */

    /**
     * Verificar si valor está vacío
     */
    private function isEmpty($value): bool {
        return $value === null || $value === '' || (is_array($value) && empty($value));
    }

    /**
     * Obtener valor del campo
     */
    private function getValue(string $field) {
        // Soporte para notación de puntos (ej: user.name)
        if (strpos($field, '.') !== false) {
            $keys = explode('.', $field);
            $value = $this->data;

            foreach ($keys as $key) {
                if (!is_array($value) || !array_key_exists($key, $value)) {
                    return null;
                }
                $value = $value[$key];
            }

            return $value;
        }

        return $this->data[$field] ?? null;
    }

    /**
     * Agregar error
     */
    private function addError(string $field, string $rule, array $parameters = []): void {
        $message = $this->getMessage($field, $rule, $parameters);

        if (!isset($this->errors[$field])) {
            $this->errors[$field] = [];
        }

        $this->errors[$field][] = $message;
    }

    /**
     * Obtener mensaje de error
     */
    private function getMessage(string $field, string $rule, array $parameters = []): string {
        // Buscar mensaje personalizado específico
        $customKey = "{$field}.{$rule}";
        if (isset($this->customMessages[$customKey])) {
            return $this->replacePlaceholders($this->customMessages[$customKey], $field, $parameters);
        }

        // Buscar mensaje personalizado general
        if (isset($this->customMessages[$rule])) {
            return $this->replacePlaceholders($this->customMessages[$rule], $field, $parameters);
        }

        // Usar mensaje por defecto
        $defaultMessage = $this->defaultMessages[$rule] ?? 'El campo :field no es válido.';
        return $this->replacePlaceholders($defaultMessage, $field, $parameters);
    }

    /**
     * Reemplazar placeholders en mensaje
     */
    private function replacePlaceholders(string $message, string $field, array $parameters = []): string {
        $message = str_replace(':field', $this->getFieldName($field), $message);

        // Reemplazar parámetros específicos según la regla
        if (!empty($parameters)) {
            $message = str_replace(':min', $parameters[0] ?? '', $message);
            $message = str_replace(':max', $parameters[0] ?? '', $message);
            $message = str_replace(':size', $parameters[0] ?? '', $message);
            $message = str_replace(':other', $parameters[0] ?? '', $message);
            $message = str_replace(':values', implode(', ', $parameters), $message);
            $message = str_replace(':mimes', implode(', ', $parameters), $message);

            // Para regla 'between'
            if (count($parameters) >= 2) {
                $message = str_replace(':min', $parameters[0], $message);
                $message = str_replace(':max', $parameters[1], $message);
            }
        }

        return $message;
    }

    /**
     * Obtener nombre legible del campo
     */
    private function getFieldName(string $field): string {
        // Convertir snake_case a palabras legibles
        $name = str_replace(['_', '.'], ' ', $field);
        return ucfirst($name);
    }

    /**
     * =============================================================================
     * MÉTODOS PÚBLICOS
     * =============================================================================
     */

    /**
     * Verificar si la validación falló
     */
    public function fails(): bool {
        return !$this->validate();
    }

    /**
     * Verificar si la validación pasó
     */
    public function passes(): bool {
        return $this->validate();
    }

    /**
     * Obtener todos los errores
     */
    public function errors(): array {
        return $this->errors;
    }

    /**
     * Obtener errores de un campo específico
     */
    public function getErrors(string $field): array {
        return $this->errors[$field] ?? [];
    }

    /**
     * Obtener primer error de un campo
     */
    public function getFirstError(string $field): ?string {
        $errors = $this->getErrors($field);
        return $errors[0] ?? null;
    }

    /**
     * Verificar si un campo tiene errores
     */
    public function hasError(string $field): bool {
        return isset($this->errors[$field]) && !empty($this->errors[$field]);
    }

    /**
     * Obtener datos validados (solo campos que pasaron validación)
     */
    public function validated(): array {
        if (!empty($this->errors)) {
            throw new RuntimeException('No se pueden obtener datos validados: la validación falló');
        }

        $validated = [];
        foreach ($this->rules as $field => $rules) {
            $value = $this->getValue($field);
            if ($value !== null) {
                $this->setNestedValue($validated, $field, $value);
            }
        }

        return $validated;
    }

    /**
     * Establecer valor anidado usando notación de puntos
     */
    private function setNestedValue(array &$array, string $field, $value): void {
        if (strpos($field, '.') === false) {
            $array[$field] = $value;
            return;
        }

        $keys = explode('.', $field);
        $current = &$array;

        foreach ($keys as $key) {
            if (!isset($current[$key]) || !is_array($current[$key])) {
                $current[$key] = [];
            }
            $current = &$current[$key];
        }

        $current = $value;
    }

    /**
     * =============================================================================
     * VALIDACIONES PERSONALIZADAS
     * =============================================================================
     */

    /**
     * Agregar regla personalizada
     */
    public function addRule(string $name, callable $callback, string $message = null): void {
        $this->customRules[$name] = $callback;

        if ($message) {
            $this->defaultMessages[$name] = $message;
        }
    }

    /**
     * Validar con regla personalizada
     */
    protected function validateCustom(string $field, $value, array $parameters): bool {
        $ruleName = $parameters[0] ?? '';

        if (!isset($this->customRules[$ruleName])) {
            return false;
        }

        $callback = $this->customRules[$ruleName];
        return $callback($value, $this->data);
    }

    /**
     * =============================================================================
     * VALIDACIONES DE BASE DE DATOS
     * =============================================================================
     */

    /**
     * Validar que valor sea único en base de datos
     */
    protected function validateUnique(string $field, $value, array $parameters): bool {
        if ($this->isEmpty($value)) return true;

        $table = $parameters[0] ?? $field . 's';
        $column = $parameters[1] ?? $field;
        $ignoreId = $parameters[2] ?? null;

        try {
            $pdo = getPDO();
            $sql = "SELECT COUNT(*) FROM {$table} WHERE {$column} = ?";
            $params = [$value];

            if ($ignoreId) {
                $sql .= " AND id != ?";
                $params[] = $ignoreId;
            }

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);

            return $stmt->fetchColumn() == 0;
        } catch (\Exception $e) {
            file_log('error', 'Error en validación unique: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Validar que valor exista en base de datos
     */
    protected function validateExists(string $field, $value, array $parameters): bool {
        if ($this->isEmpty($value)) return true;

        $table = $parameters[0] ?? $field . 's';
        $column = $parameters[1] ?? 'id';

        try {
            $pdo = getPDO();
            $sql = "SELECT COUNT(*) FROM {$table} WHERE {$column} = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$value]);

            return $stmt->fetchColumn() > 0;
        } catch (\Exception $e) {
            file_log('error', 'Error en validación exists: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * =============================================================================
     * MÉTODOS ESTÁTICOS ÚTILES
     * =============================================================================
     */

    /**
     * Validación rápida de email
     */
    public static function isEmail(string $email): bool {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Validación rápida de URL
     */
    public static function isUrl(string $url): bool {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * Validación rápida de número
     */
    public static function isNumeric($value): bool {
        return is_numeric($value);
    }

    /**
     * Validación rápida de entero
     */
    public static function isInteger($value): bool {
        return filter_var($value, FILTER_VALIDATE_INT) !== false;
    }

    /**
     * Sanitizar string
     */
    public static function sanitize(string $input, array $options = []): string {
        $default = [
            'trim' => true,
            'strip_tags' => true,
            'htmlspecialchars' => true,
            'remove_extra_spaces' => true
        ];

        $options = array_merge($default, $options);

        if ($options['trim']) {
            $input = trim($input);
        }

        if ($options['strip_tags']) {
            $input = strip_tags($input);
        }

        if ($options['htmlspecialchars']) {
            $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
        }

        if ($options['remove_extra_spaces']) {
            $input = preg_replace('/\s+/', ' ', $input);
        }

        return $input;
    }

    /**
     * =============================================================================
     * VALIDADOR DE FORMULARIOS ESPECÍFICOS
     * =============================================================================
     */

    /**
     * Validar formulario de login
     */
    public static function validateLogin(array $data): array {
        $validator = self::make($data, [
            'email' => 'required|email',
            'password' => 'required|min:6'
        ], [
            'email.required' => 'El email es obligatorio.',
            'email.email' => 'Ingresa un email válido.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.'
        ]);

        return [
            'valid' => $validator->passes(),
            'errors' => $validator->errors(),
            'data' => $validator->passes() ? $validator->validated() : []
        ];
    }

    /**
     * Validar formulario de registro
     */
    public static function validateRegister(array $data): array {
        $validator = self::make($data, [
            'name' => 'required|min:2|max:50|alpha',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'terms' => 'required|boolean'
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'name.alpha' => 'El nombre solo debe contener letras.',
            'email.unique' => 'Este email ya está registrado.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'terms.required' => 'Debes aceptar los términos y condiciones.'
        ]);

        return [
            'valid' => $validator->passes(),
            'errors' => $validator->errors(),
            'data' => $validator->passes() ? $validator->validated() : []
        ];
    }

    /**
     * Validar cambio de contraseña
     */
    public static function validatePasswordChange(array $data): array {
        $validator = self::make($data, [
            'current_password' => 'required',
            'new_password' => 'required|min:8|different:current_password|confirmed'
        ], [
            'current_password.required' => 'La contraseña actual es obligatoria.',
            'new_password.min' => 'La nueva contraseña debe tener al menos 8 caracteres.',
            'new_password.different' => 'La nueva contraseña debe ser diferente a la actual.',
            'new_password.confirmed' => 'Las contraseñas no coinciden.'
        ]);

        return [
            'valid' => $validator->passes(),
            'errors' => $validator->errors(),
            'data' => $validator->passes() ? $validator->validated() : []
        ];
    }

    /**
     * =============================================================================
     * UTILIDADES DE DEBUGGING
     * =============================================================================
     */

    /**
     * Obtener información de debugging
     */
    public function getDebugInfo(): array {
        return [
            'data' => $this->data,
            'rules' => $this->rules,
            'errors' => $this->errors,
            'custom_messages' => $this->customMessages,
            'field_count' => count($this->data),
            'rule_count' => count($this->rules),
            'error_count' => count($this->errors),
            'validation_passed' => empty($this->errors)
        ];
    }

    /**
     * Mostrar información de debugging (solo en modo debug)
     */
    public function debug(): void {
        if (!is_debug()) {
            return;
        }

        $info = $this->getDebugInfo();

        echo '<div style="background: #f0f0f0; border: 1px solid #ccc; padding: 10px; margin: 10px; font-family: monospace; font-size: 12px;">';
        echo '<h4>Validator Debug Info:</h4>';
        echo '<pre>' . print_r($info, true) . '</pre>';
        echo '</div>';
    }

    /**
     * Convertir errores a formato JSON
     */
    public function toJson(): string {
        return json_encode([
            'valid' => empty($this->errors),
            'errors' => $this->errors
        ], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Obtener resumen de errores
     */
    public function getErrorSummary(): array {
        $summary = [];

        foreach ($this->errors as $field => $fieldErrors) {
            $summary[$field] = [
                'field' => $field,
                'error_count' => count($fieldErrors),
                'first_error' => $fieldErrors[0] ?? null,
                'all_errors' => $fieldErrors
            ];
        }

        return $summary;
    }
}
