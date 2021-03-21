<?php

namespace Drupal\tintorero\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class ContactoProductoForm extends FormBase
{


    public function buildForm(array $form, FormStateInterface $form_state)
    {

        $form['nombre'] = array(
            '#type'          => 'textfield',
            '#title'         => 'Nombre',
        );
        $form['email'] = array(
            '#type'          => 'textfield',
            '#title'         => 'Email',
        );
        $form['comentarios'] = array(
            '#type'          => 'textarea',
            '#title'         => 'comentarios',
        );
        $form['submit'] = array(
            '#type'          => 'submit',
            '#value' => 'Enviar',
        );
        return $form;
    }
    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state)
    {
    }
    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
    }

    public function getFormId()
    {
        return 'contacto_producto_form';
    }
}
