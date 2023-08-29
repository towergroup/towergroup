window.validationSchema = [
    {
        form: '#backcall-form',
        schema:
            {
                phone:
                    {
                        presence:
                            {
                                allowEmpty: false,
                                message: 'Незаполненное поле'
                            },
                        length:
                            {
                                minimum: 7,
                                message: 'Телефон должен содержать минимум 7 цифр'
                            }
                    },
                privacy:
                    {
                        inclusion:
                            {
                                within: [true],
                                message: 'Необходимо Ваше согласие на обработку персональных данных'
                            }
                    }
            }
    },
    {
        form: '#backcall-form-secondary',
        schema:
            {
                phone:
                    {
                        presence:
                            {
                                allowEmpty: false,
                                message: 'Незаполненное поле'
                            },
                        length:
                            {
                                minimum: 7,
                                message: 'Телефон должен содержать минимум 7 цифр'
                            }
                    },
                privacy:
                    {
                        inclusion:
                            {
                                within: [true],
                                message: 'Необходимо Ваше согласие на обработку персональных данных'
                            }
                    }
            }
    },
    {
        form: '#landing-broker-form',
        schema:
            {
                phone:
                    {
                        presence:
                            {
                                allowEmpty: false,
                                message: 'Незаполненное поле'
                            },
                        length:
                            {
                                minimum: 7,
                                message: 'Телефон должен содержать минимум 7 цифр'
                            }
                    },
                privacy:
                    {
                        inclusion:
                            {
                                within: [true],
                                message: 'Необходимо Ваше согласие на обработку персональных данных'
                            }
                    }
            }
    },
    {
        form: '#request-quiz-form',
        schema:
            {
                phone:
                    {
                        presence:
                            {
                                allowEmpty: false,
                                message: 'Незаполненное поле'
                            },
                        length:
                            {
                                minimum: 7,
                                message: 'Телефон должен содержать минимум 7 цифр'
                            }
                    },
                privacy:
                    {
                        inclusion:
                            {
                                within: [true],
                                message: 'Необходимо Ваше согласие на обработку персональных данных'
                            }
                    }
            }
    },
    {
        form: '#catalog-quiz-form',
        schema:
            {
                phone:
                    {
                        presence:
                            {
                                allowEmpty: false,
                                message: 'Незаполненное поле'
                            },
                        length:
                            {
                                minimum: 7,
                                message: 'Телефон должен содержать минимум 7 цифр'
                            }
                    },
                privacy:
                    {
                        inclusion:
                            {
                                within: [true],
                                message: 'Необходимо Ваше согласие на обработку персональных данных'
                            }
                    }
            }
    },
    {
        form: '#feedback-form',
        schema:
            {
                phone:
                    {
                        presence:
                            {
                                allowEmpty: false,
                                message: 'Незаполненное поле'
                            },
                        length:
                            {
                                minimum: 7,
                                message: 'Телефон должен содержать минимум 7 цифр'
                            }
                    },
                privacy:
                    {
                        inclusion:
                            {
                                within: [true],
                                message: 'Необходимо Ваше согласие на обработку персональных данных'
                            }
                    }
            }
    },
    {
        form: '#about-broker-form',
        schema:
            {
                phone:
                    {
                        presence:
                            {
                                allowEmpty: false,
                                message: 'Незаполненное поле'
                            },
                        length:
                            {
                                minimum: 7,
                                message: 'Телефон должен содержать минимум 7 цифр'
                            }
                    },
                privacy:
                    {
                        inclusion:
                            {
                                within: [true],
                                message: 'Необходимо Ваше согласие на обработку персональных данных'
                            }
                    }
            }
    },
    {
        form: '#broker-form',
        schema:
            {
                phone:
                    {
                        presence:
                            {
                                allowEmpty: false,
                                message: 'Незаполненное поле'
                            },
                        length:
                            {
                                minimum: 7,
                                message: 'Телефон должен содержать минимум 7 цифр'
                            }
                    },
                privacy:
                    {
                        inclusion:
                            {
                                within: [true],
                                message: 'Необходимо Ваше согласие на обработку персональных данных'
                            }
                    }
            }
    },
    {
        form: '#viewing-form',
        schema:
            {
                phone:
                    {
                        presence:
                            {
                                allowEmpty: false,
                                message: 'Незаполненное поле'
                            },
                        length:
                            {
                                minimum: 7,
                                message: 'Телефон должен содержать минимум 7 цифр'
                            }
                    },
                privacy:
                    {
                        inclusion:
                            {
                                within: [true],
                                message: 'Необходимо Ваше согласие на обработку персональных данных'
                            }
                    }
            }
    },
    {
        form: '#excursion-form',
        schema:
            {
                phone:
                    {
                        presence:
                            {
                                allowEmpty: false,
                                message: 'Незаполненное поле'
                            },
                        length:
                            {
                                minimum: 7,
                                message: 'Телефон должен содержать минимум 7 цифр'
                            }
                    },
                privacy:
                    {
                        inclusion:
                            {
                                within: [true],
                                message: 'Необходимо Ваше согласие на обработку персональных данных'
                            }
                    }
            }
    },
    {
        form: '#contact-form',
        schema:
            {
                phone:
                    {
                        presence:
                            {
                                allowEmpty: false,
                                message: 'Незаполненное поле'
                            },
                        length:
                            {
                                minimum: 7,
                                message: 'Телефон должен содержать минимум 7 цифр'
                            }
                    },
                email:
                    {
                        email:
                            {
                                message: 'Введите верную почту'
                            }
                    },
                privacy:
                    {
                        inclusion:
                            {
                                within: [true],
                                message: 'Необходимо Ваше согласие на обработку персональных данных'
                            }
                    }
            }
    },
    {
        form: '#reserve-form',
        schema:
            {
                phone:
                    {
                        presence:
                            {
                                allowEmpty: false,
                                message: 'Незаполненное поле'
                            },
                        length:
                            {
                                minimum: 7,
                                message: 'Телефон должен содержать минимум 7 цифр'
                            }
                    },
                privacy:
                    {
                        inclusion:
                            {
                                within: [true],
                                message: 'Необходимо Ваше согласие на обработку персональных данных'
                            }
                    }
            }
    },
    {
        form: '#help-form',
        schema:
            {
                phone:
                    {
                        presence:
                            {
                                allowEmpty: false,
                                message: 'Незаполненное поле'
                            },
                        length:
                            {
                                minimum: 7,
                                message: 'Телефон должен содержать минимум 7 цифр'
                            }
                    },
                privacy:
                    {
                        inclusion:
                            {
                                within: [true],
                                message: 'Необходимо Ваше согласие на обработку персональных данных'
                            }
                    }
            }
    },
    {
        form: '#download-form',
        schema:
            {
                phone:
                    {
                        presence:
                            {
                                allowEmpty: false,
                                message: 'Незаполненное поле'
                            },
                        length:
                            {
                                minimum: 7,
                                message: 'Телефон должен содержать минимум 7 цифр'
                            }
                    },
                privacy:
                    {
                        inclusion:
                            {
                                within: [true],
                                message: 'Необходимо Ваше согласие на обработку персональных данных'
                            }
                    }
            }
    },
    {
        form: '#presentation-form',
        schema:
            {
                phone:
                    {
                        presence:
                            {
                                allowEmpty: false,
                                message: 'Незаполненное поле'
                            },
                        length:
                            {
                                minimum: 7,
                                message: 'Телефон должен содержать минимум 7 цифр'
                            }
                    }
            }
    },
    {
        form: '#detail-presentation',
        schema:
            {
                phone:
                    {
                        presence:
                            {
                                allowEmpty: false,
                                message: 'Незаполненное поле'
                            },
                        length:
                            {
                                minimum: 7,
                                message: 'Телефон должен содержать минимум 7 цифр'
                            }
                    },
                privacy:
                    {
                        inclusion:
                            {
                                within: [true],
                                message: 'Необходимо Ваше согласие на обработку персональных данных'
                            }
                    }
            }
    },
    {
        form: '#detail-presentation-modal',
        schema:
            {
                phone:
                    {
                        presence:
                            {
                                allowEmpty: false,
                                message: 'Незаполненное поле'
                            },
                        length:
                            {
                                minimum: 7,
                                message: 'Телефон должен содержать минимум 7 цифр'
                            }
                    },
                privacy:
                    {
                        inclusion:
                            {
                                within: [true],
                                message: 'Необходимо Ваше согласие на обработку персональных данных'
                            }
                    }
            }
    },
    {
        form: '#credit-form',
        schema:
            {
                phone:
                    {
                        presence:
                            {
                                allowEmpty: false,
                                message: 'Незаполненное поле'
                            },
                        length:
                            {
                                minimum: 7,
                                message: 'Телефон должен содержать минимум 7 цифр'
                            }
                    },
                privacy:
                    {
                        inclusion:
                            {
                                within: [true],
                                message: 'Необходимо Ваше согласие на обработку персональных данных'
                            }
                    }
            }
    },
    {
        form: '#detail-excursion',
        schema:
            {
                phone:
                    {
                        presence:
                            {
                                allowEmpty: false,
                                message: 'Незаполненное поле'
                            },
                        length:
                            {
                                minimum: 7,
                                message: 'Телефон должен содержать минимум 7 цифр'
                            }
                    }
            }
    },
    {
        form: '#object-new-form',
        schema:
            {
                phone:
                    {
                        presence:
                            {
                                allowEmpty: false,
                                message: 'Незаполненное поле'
                            },
                        length:
                            {
                                minimum: 7,
                                message: 'Телефон должен содержать минимум 7 цифр'
                            }
                    },
                privacy:
                    {
                        inclusion:
                            {
                                within: [true],
                                message: 'Необходимо Ваше согласие на обработку персональных данных'
                            }
                    }
            }
    },
    {
        form: '#vacancy-form',
        schema:
            {
                phone:
                    {
                        presence:
                            {
                                allowEmpty: false,
                                message: 'Незаполненное поле'
                            },
                        length:
                            {
                                minimum: 7,
                                message: 'Телефон должен содержать минимум 7 цифр'
                            }
                    },
                privacy:
                    {
                        inclusion:
                            {
                                within: [true],
                                message: 'Необходимо Ваше согласие на обработку персональных данных'
                            }
                    }
            }
    },
    {
        form: '#service-form',
        schema:
            {
                phone:
                    {
                        presence:
                            {
                                allowEmpty: false,
                                message: 'Незаполненное поле'
                            },
                        length:
                            {
                                minimum: 7,
                                message: 'Телефон должен содержать минимум 7 цифр'
                            }
                    },
                privacy:
                    {
                        inclusion:
                            {
                                within: [true],
                                message: 'Необходимо Ваше согласие на обработку персональных данных'
                            }
                    }
            }
    },
    {
        form: '#rating-form',
        schema:
            {
                phone:
                    {
                        presence:
                            {
                                allowEmpty: false,
                                message: 'Незаполненное поле'
                            },
                        length:
                            {
                                minimum: 7,
                                message: 'Телефон должен содержать минимум 7 цифр'
                            }
                    },
                rating:
                    {
                        presence:
                            {
                                allowEmpty: false,
                                message: 'Незаполненное поле'
                            }
                    },
                message:
                    {
                        presence:
                            {
                                allowEmpty: false,
                                message: 'Незаполненное поле'
                            }
                    },
                privacy:
                    {
                        inclusion:
                            {
                                within: [true],
                                message: 'Необходимо Ваше согласие на обработку персональных данных'
                            }
                    }
            }
    },
    {
        form: '#contacts-us-form',
        schema:
            {
                phone:
                    {
                        presence:
                            {
                                allowEmpty: false,
                                message: 'Незаполненное поле'
                            },
                        length:
                            {
                                minimum: 7,
                                message: 'Телефон должен содержать минимум 7 цифр'
                            }
                    },
                privacy:
                    {
                        inclusion:
                            {
                                within: [true],
                                message: 'Необходимо Ваше согласие на обработку персональных данных'
                            }
                    }
            }
    }]