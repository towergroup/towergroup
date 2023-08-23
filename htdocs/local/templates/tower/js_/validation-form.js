window.validationSchema = [
    {
        form: '#backcall-form',
        schema:
            {
                name:
                    {
                        presence:
                            {
                                allowEmpty: false,
                                message: 'Незаполненное поле'
                            },
                        format:
                            {
                                pattern: /^[aA-zZ|aA-яЯ|ёЁ\s]+$/,
                                flags: 'i',
                                message: 'Имя должно включать символы только русского и англиского алфавита'
                            }
                    },
                phone:
                    {
                        presence:
                            {
                                allowEmpty: false,
                                message: 'Незаполненное поле'
                            },
                        length:
                            {
                                is: 18,
                                message: 'Телефон должен содержать 11 цифр'
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
                name:
                    {
                        presence:
                            {
                                allowEmpty: false,
                                message: 'Незаполненное поле'
                            },
                        format:
                            {
                                pattern: /^[aA-zZ|aA-яЯ|ёЁ\s]+$/,
                                flags: 'i',
                                message: 'Имя должно включать символы только русского и англиского алфавита'
                            }
                    },
                phone:
                    {
                        presence:
                            {
                                allowEmpty: false,
                                message: 'Незаполненное поле'
                            },
                        length:
                            {
                                is: 18,
                                message: 'Телефон должен содержать 11 цифр'
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
                name:
                    {
                        presence:
                            {
                                allowEmpty: false,
                                message: 'Незаполненное поле'
                            },
                        format:
                            {
                                pattern: /^[aA-zZ|aA-яЯ|ёЁ\s]+$/,
                                flags: 'i',
                                message: 'Имя должно включать символы только русского и англиского алфавита'
                            }
                    },
                phone:
                    {
                        presence:
                            {
                                allowEmpty: false,
                                message: 'Незаполненное поле'
                            },
                        length:
                            {
                                is: 18,
                                message: 'Телефон должен содержать 11 цифр'
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
                name:
                    {
                        presence:
                            {
                                allowEmpty: false,
                                message: 'Незаполненное поле'
                            },
                        format:
                            {
                                pattern: /^[aA-zZ|aA-яЯ|ёЁ\s]+$/,
                                flags: 'i',
                                message: 'Имя должно включать символы только русского и англиского алфавита'
                            }
                    },
                phone:
                    {
                        presence:
                            {
                                allowEmpty: false,
                                message: 'Незаполненное поле'
                            },
                        length:
                            {
                                is: 18,
                                message: 'Телефон должен содержать 11 цифр'
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
                name:
                    {
                        presence:
                            {
                                allowEmpty: false,
                                message: 'Незаполненное поле'
                            },
                        format:
                            {
                                pattern: /^[aA-zZ|aA-яЯ|ёЁ\s]+$/,
                                flags: 'i',
                                message: 'Имя должно включать символы только русского и англиского алфавита'
                            }
                    },
                phone:
                    {
                        presence:
                            {
                                allowEmpty: false,
                                message: 'Незаполненное поле'
                            },
                        length:
                            {
                                is: 18,
                                message: 'Телефон должен содержать 11 цифр'
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
                name:
                    {
                        presence:
                            {
                                allowEmpty: false,
                                message: 'Незаполненное поле'
                            },
                        format:
                            {
                                pattern: /^[aA-zZ|aA-яЯ|ёЁ\s]+$/,
                                flags: 'i',
                                message: 'Имя должно включать символы только русского и англиского алфавита'
                            }
                    },
                phone:
                    {
                        presence:
                            {
                                allowEmpty: false,
                                message: 'Незаполненное поле'
                            },
                        length:
                            {
                                is: 18,
                                message: 'Телефон должен содержать 11 цифр'
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
                name:
                    {
                        presence:
                            {
                                allowEmpty: false,
                                message: 'Незаполненное поле'
                            },
                        format:
                            {
                                pattern: /^[aA-zZ|aA-яЯ|ёЁ\s]+$/,
                                flags: 'i',
                                message: 'Имя должно включать символы только русского и англиского алфавита'
                            }
                    },
                phone:
                    {
                        presence:
                            {
                                allowEmpty: false,
                                message: 'Незаполненное поле'
                            },
                        length:
                            {
                                is: 18,
                                message: 'Телефон должен содержать 11 цифр'
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
                name:
                    {
                        presence:
                            {
                                allowEmpty: false,
                                message: 'Незаполненное поле'
                            },
                        format:
                            {
                                pattern: /^[aA-zZ|aA-яЯ|ёЁ\s]+$/,
                                flags: 'i',
                                message: 'Имя должно включать символы только русского и англиского алфавита'
                            }
                    },
                phone:
                    {
                        presence:
                            {
                                allowEmpty: false,
                                message: 'Незаполненное поле'
                            },
                        length:
                            {
                                is: 18,
                                message: 'Телефон должен содержать 11 цифр'
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
                name:
                    {
                        presence:
                            {
                                allowEmpty: false,
                                message: 'Незаполненное поле'
                            },
                        format:
                            {
                                pattern: /^[aA-zZ|aA-яЯ|ёЁ\s]+$/,
                                flags: 'i',
                                message: 'Имя должно включать символы только русского и англиского алфавита'
                            }
                    },
                phone:
                    {
                        presence:
                            {
                                allowEmpty: false,
                                message: 'Незаполненное поле'
                            },
                        length:
                            {
                                is: 18,
                                message: 'Телефон должен содержать 11 цифр'
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
                name:
                    {
                        presence:
                            {
                                allowEmpty: false,
                                message: 'Незаполненное поле'
                            },
                        format:
                            {
                                pattern: /^[aA-zZ|aA-яЯ|ёЁ\s]+$/,
                                flags: 'i',
                                message: 'Имя должно включать символы только русского и англиского алфавита'
                            }
                    },
                phone:
                    {
                        presence:
                            {
                                allowEmpty: false,
                                message: 'Незаполненное поле'
                            },
                        length:
                            {
                                is: 18,
                                message: 'Телефон должен содержать 11 цифр'
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
                name:
                    {
                        presence:
                            {
                                allowEmpty: false,
                                message: 'Незаполненное поле'
                            },
                        format:
                            {
                                pattern: /^[aA-zZ|aA-яЯ|ёЁ\s]+$/,
                                flags: 'i',
                                message: 'Имя должно включать символы только русского и англиского алфавита'
                            }
                    },
                phone:
                    {
                        presence:
                            {
                                allowEmpty: false,
                                message: 'Незаполненное поле'
                            },
                        length:
                            {
                                is: 18,
                                message: 'Телефон должен содержать 11 цифр'
                            }
                    }
            }
    },
    {
        form: '#object-new-form',
        schema:
            {
                name:
                    {
                        presence:
                            {
                                allowEmpty: false,
                                message: 'Незаполненное поле'
                            },
                        format:
                            {
                                pattern: /^[aA-zZ|aA-яЯ|ёЁ\s]+$/,
                                flags: 'i',
                                message: 'Имя должно включать символы только русского и англиского алфавита'
                            }
                    },
                phone:
                    {
                        presence:
                            {
                                allowEmpty: false,
                                message: 'Незаполненное поле'
                            },
                        length:
                            {
                                is: 18,
                                message: 'Телефон должен содержать 11 цифр'
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
                name:
                    {
                        presence:
                            {
                                allowEmpty: false,
                                message: 'Незаполненное поле'
                            },
                        format:
                            {
                                pattern: /^[aA-zZ|aA-яЯ|ёЁ\s]+$/,
                                flags: 'i',
                                message: 'Имя должно включать символы только русского и англиского алфавита'
                            }
                    },
                phone:
                    {
                        presence:
                            {
                                allowEmpty: false,
                                message: 'Незаполненное поле'
                            },
                        length:
                            {
                                is: 18,
                                message: 'Телефон должен содержать 11 цифр'
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
                name:
                    {
                        presence:
                            {
                                allowEmpty: false,
                                message: 'Незаполненное поле'
                            },
                        format:
                            {
                                pattern: /^[aA-zZ|aA-яЯ|ёЁ\s]+$/,
                                flags: 'i',
                                message: 'Имя должно включать символы только русского и англиского алфавита'
                            }
                    },
                phone:
                    {
                        presence:
                            {
                                allowEmpty: false,
                                message: 'Незаполненное поле'
                            },
                        length:
                            {
                                is: 18,
                                message: 'Телефон должен содержать 11 цифр'
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
                name:
                    {
                        presence:
                            {
                                allowEmpty: false,
                                message: 'Незаполненное поле'
                            },
                        format:
                            {
                                pattern: /^[aA-zZ|aA-яЯ|ёЁ\s]+$/,
                                flags: 'i',
                                message: 'Имя должно включать символы только русского и англиского алфавита'
                            }
                    },
                phone:
                    {
                        presence:
                            {
                                allowEmpty: false,
                                message: 'Незаполненное поле'
                            },
                        length:
                            {
                                is: 18,
                                message: 'Телефон должен содержать 11 цифр'
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
                name:
                    {
                        presence:
                            {
                                allowEmpty: false,
                                message: 'Незаполненное поле'
                            },
                        format:
                            {
                                pattern: /^[aA-zZ|aA-яЯ|ёЁ\s]+$/,
                                flags: 'i',
                                message: 'Имя должно включать символы только русского и англиского алфавита'
                            }
                    },
                phone:
                    {
                        presence:
                            {
                                allowEmpty: false,
                                message: 'Незаполненное поле'
                            },
                        length:
                            {
                                is: 18,
                                message: 'Телефон должен содержать 11 цифр'
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