const csrfToken = document.head.querySelector("[name~=csrf-token][content]").content;

const inputsFromJson = (json, prefix = '') => {
    let inputs = [];

    for (const key in json) {
        const name = `${prefix}[${key}]`;
        const value = json[key];

        if (typeof value === 'object' || typeof value === 'array') {
            inputs = [...inputs, ...inputsFromJson(value, `${prefix}[${key}]`)];
            continue;
        }

        const input = document.createElement('input');

        input.type = 'hidden';
        input.name = name;
        input.value = value;

        inputs.push(input);
    }

    return inputs;
}

const pageReload = () => document.querySelectorAll('.page-reload').forEach(link => {
    link.addEventListener('click', event => {
        event.preventDefault();

        location.reload();
    });
});

const closeModal = () => document.querySelectorAll('.modal-close').forEach(link => {
    link.addEventListener('click', event => {
        event.preventDefault();

        document.querySelector('body').setAttribute('style', '');

        document.querySelector('.modal-container').setAttribute('style', '');
        document.querySelector('.modal-container > div').innerHTML = '';
    });
});

const openAsyncModal = () => document.querySelectorAll('.async-modal-link').forEach(link => {
    link.addEventListener('click', event => {
        event.preventDefault();

        const target = event.currentTarget;

        if (target.dataset.disabled == 1) {
            return;
        }

        let params = {
            headers: {
                "X-CSRF-Token": csrfToken,
            }
        };
        let url = target.href;

        if (target.dataset.is_form) {
            let form = target.form
                ? target.form
                : target.closest('form');

            if (!form) {
                return;
            }

            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            params.method = form.method;
            params.body = new FormData(form);

            url = form.action;
        }

        target.dataset.disabled = 1;

        fetch(url, params)
            .then(response => {
                if (!response.ok) {
                    alert('Error status code ' + response.status);

                    target.dataset.disabled = 0;

                    throw new Error('Server side exception');
                }

                return response.text();
            })
            .then(html => {
                document.querySelector('body').setAttribute('style', 'overflow: hidden');

                document.querySelector('.modal-container > div').innerHTML = html;
                document.querySelector('.modal-container').setAttribute('style', 'display: block;');

                init();

                target.dataset.disabled = 0;
            });
    });
});

const asyncFileUploading = (file, url) => {
    const formData = new FormData();
    formData.append('file', file);

    let params = {
        headers: {
            "Accept": "application/json",
            "X-CSRF-Token": csrfToken,
        },
        method: 'POST',
        body: formData,
    };

    return fetch(url, params)
        .then(response => {
            if (!response.ok) {
                response.text().then(text => alert(text));

                throw new Error('Server side exception');
            }

            return response.json();
        });
};

const mediaDisplay = (type, path, isVideoAvailable, image, video) => {
    if (isVideoAvailable && type.startsWith("video/")) {
        image.setAttribute('style', 'display: none');
        video?.setAttribute('style', 'display: block');
        video.src = path;

        return;
    }

    image.setAttribute('style', 'display: block');
    video?.setAttribute('style', 'display: none');
    image.src = path;
};

const fileInput = () => document.querySelectorAll('.file:not(.multiple) input').forEach(input => {
    input.addEventListener('change', async event => {
        const input = event.currentTarget;
        const asyncInput = input.closest('.input').querySelector('input[type="hidden"]');
        const asyncUrl = input.dataset.async_url;
        const isVideoAvailable = input.dataset.is_video_available;
        const image = input.closest('.file').querySelector('img');
        const video = input.closest('.file').querySelector('video');

        if (!input.files.length) {
            return;
        }

        const reader = new FileReader();
        const file = input.files[0];

        if (asyncUrl) {
            await asyncFileUploading(file, asyncUrl)
                .then(json => {
                    mediaDisplay(json.mime, json.path, isVideoAvailable, image, video);

                    asyncInput.value = json.path;
                });

            return;
        }

        reader.addEventListener('load', event => {
            const input = event.currentTarget;

            if (!image && !video) {
                return;
            }

            mediaDisplay(file.type, input.result, isVideoAvailable, image, video);
        });

        reader.readAsDataURL(file);
    });
});

const fileMultipleInput = () => document.querySelectorAll('.file.multiple input[type="file"]').forEach(input => {
    const container = input.closest('.container');
    const wrapper = input.closest('.file');
    const imagesBlock = input.closest('.file').querySelector('.images');
    const template = input.closest('.file').dataset.template;

    const orderingContent = JSON.parse(wrapper.dataset.value);

    if (imagesBlock.children.length !== Object.keys(orderingContent.order).length) {
        imagesBlock.innerHTML = '';

        for (let path in orderingContent.order) {
            imagesBlock.innerHTML += template
                .replace('{image}', path)
                .replace('{order}', orderingContent.order[path]);

            if (orderingContent.deleted[path]) {
                container.classList.toggle('removed');
            }
        }
    }

    fileMultipleInputUpdateValue(wrapper, orderingContent);

    input.addEventListener('change', async event => {
        const input = event.currentTarget;
        const wrapper = input.closest('.file');
        const orderingContent = JSON.parse(wrapper.dataset.value);
        const asyncUrl = input.dataset.async_url;
        const imagesBlock = wrapper.querySelector('.images');
        const template = wrapper.dataset.template;

        if (!input.files.length) {
            return;
        }

        if (!asyncUrl) {
            imagesBlock.innerHTML = '';
        }

        const promises = [];

        for (let i = 0; i < input.files.length; i++) {
            const file = input.files[i];
            const reader = new FileReader();

            if (asyncUrl) {
                promises.push(
                    asyncFileUploading(file, asyncUrl).then(json => {
                        let position = Object.keys(orderingContent.order).length;
                        orderingContent.order[json.path] = position;

                        imagesBlock.innerHTML += template.replace('{image}', json.path).replace('{order}', position);
                    }),
                );

                continue;
            }

            reader.addEventListener('load', event => {
                const input = event.currentTarget;

                imagesBlock.innerHTML += `<img src="${input.result}" alt="">`;
            });

            reader.readAsDataURL(file);
        }

        await Promise.all(promises);

        if (asyncUrl) {
            fileMultipleInputUpdateValue(wrapper, orderingContent);

            imagesBlock
                .querySelectorAll('.container .delete-image')
                .forEach(button => button.addEventListener('click', fileMultipleInputDeleteActionEvent));
            imagesBlock
                .querySelectorAll('.container .move-image-left')
                .forEach(button => button.addEventListener('click', fileMultipleInputMoveActionEvent('left')));
            imagesBlock
                .querySelectorAll('.container .move-image-right')
                .forEach(button => button.addEventListener('click', fileMultipleInputMoveActionEvent('right')));
        }
    });
});

const fileMultipleInputUpdateValue = (wrapper, orderingContent) => {
    console.log('#1', orderingContent);
    wrapper.dataset.value = JSON.stringify(orderingContent);

    let images = [];

    for (const path in orderingContent.order) {
        const position = orderingContent.order[path];
        const isDeleted = orderingContent.deleted[path];

        if (!isDeleted) {
            images[position] = path;
        }
    }

    wrapper.querySelectorAll('.input input[type="hidden"]').forEach(input => input.remove());

    inputsFromJson(images, wrapper.dataset.name).forEach(input => {
        wrapper.querySelector('.input').appendChild(input);
    });

    console.log('#2', orderingContent, inputsFromJson(orderingContent, `${wrapper.dataset.name}_extra`));
    inputsFromJson(orderingContent, `${wrapper.dataset.name}_extra`).forEach(input => {
        wrapper.querySelector('.input').appendChild(input);
    });
};

const fileMultipleInputDeleteActionEvent = event => {
    const button = event.currentTarget;
    const wrapper = button.closest('.file');
    const container = button.closest('.container');
    const image = container.querySelector('img');
    const orderingContent = JSON.parse(wrapper.dataset.value);

    orderingContent.deleted[image.src] = !orderingContent.deleted[image.src];

    fileMultipleInputUpdateValue(wrapper, orderingContent);

    container.classList.toggle('removed');
};

const fileMultipleInputDeleteActionInit = () => {
    document
        .querySelectorAll('.file.multiple .images .container .delete-image')
        .forEach(button => button.addEventListener('click', fileMultipleInputDeleteActionEvent));
};

const fileMultipleInputMoveActionEvent = direction => event => {
    const button = event.currentTarget;
    const wrapper = button.closest('.file');
    const container = button.closest('.container');
    const image = container.querySelector('img');
    const orderingContent = JSON.parse(wrapper.dataset.value);

    const currentOrder = orderingContent.order[image.src];

    const desiredOrder = direction == 'left' ? currentOrder - 1 : currentOrder + 1;

    let minOrder = 0;
    let maxOrder = Object.keys(orderingContent.order).length - 1;

    // looking for a neighbor to switch positions
    for (let path in orderingContent.order) {
        let checkedOrder = orderingContent.order[path];

        if (checkedOrder == desiredOrder) {
            orderingContent.order[path] = currentOrder;
            document
                .querySelector('img[src="' + path + '"]')
                .closest('.container')
                .style
                .order = currentOrder;
            break;
        }
    }

    if (desiredOrder < minOrder || desiredOrder > maxOrder) {
        return;
    }

    orderingContent.order[image.src] = desiredOrder;

    fileMultipleInputUpdateValue(wrapper, orderingContent);

    container.style.order = desiredOrder;
};

const fileMultipleInputMoveActionInit = () => {
    document
        .querySelectorAll('.file.multiple .images .container .move-image-left')
        .forEach(button => button.addEventListener('click', fileMultipleInputMoveActionEvent('left')));
    document
        .querySelectorAll('.file.multiple .images .container .move-image-right')
        .forEach(button => button.addEventListener('click', fileMultipleInputMoveActionEvent('right')));
};

const slugInput = () => document.querySelectorAll('.slug').forEach(input => {
    input.addEventListener('change', event => {
        const input = event.currentTarget;
        const targetSelector = input.dataset.slug_target;
        const target = document.querySelector(targetSelector);

        target.value = slugify(input.value, {
            lower: true,
            strict: true
        });
    });
});

const searchInput = () => {
    document.querySelectorAll('.search-input .search').forEach(button => {
        button.addEventListener('click', event => {
            const button = event.currentTarget;
            const select = button.closest('.select');
            const form = button.closest('form');

            if (!select) {
                form.submit();
            }
        });
    });

    document.querySelectorAll('.search-input .clear').forEach(button => {
        button.addEventListener('click', event => {
            const button = event.currentTarget;
            const input = button.closest('.search-input').querySelector('input');

            input.value = '';
        });
    })
};

const showSelectDropdown = (input) => {
    const select = input.closest('.select');
    const options = select.querySelector('.options');

    options.style.width = input.clientWidth + 'px';
    options.style.maxWidth = input.clientWidth + 'px';
    select.classList.toggle('shown');
};

const asyncLoadSingleSelectInput = (select, url, dropValue = false) => {
    const params = {
        headers: {
            "Accept": "application/json",
            "X-CSRF-Token": csrfToken,
        },
    };

    return fetch(url, params)
        .then(response => {
            if (!response.ok) {
                alert('Error status code ' + response.status);

                throw new Error('Server side exception');
            }

            return response.json();
        })
        .then(json => selectInputUpdateOptions(json, select, dropValue))
        .then(() => {
            selectInput();
            singleSelectInput();
        });
};

const asyncSearchForSelectInput = (select, searchInput) => {
    if (searchInput.dataset.disabled == 1) {
        return;
    }

    searchInput.dataset.disabled = 1;

    const query = searchInput.value;
    const asyncUrl = searchInput.dataset.async_url;
    const vanillaOptionsBlock = select.querySelector('.vanilla select');
    const selected = vanillaOptionsBlock.value;

    const url = asyncUrl
        .replace('%7Bquery%7D', query)
        .replace('%7Bselected%7D', selected);

    asyncLoadSingleSelectInput(select, url)
        .finally(() => {
            searchInput.dataset.disabled = 0;
        });
};

const selectInputUpdateOptions = (json, select, dropValue = false) => {
    const optionsBlock = select.querySelector('.options');
    const template = select.dataset.template;
    const vanillaOptionsBlock = select.querySelector('.vanilla select');
    const vanillaTemplate = select.dataset.vanilla_template;
    const vanillaValue = vanillaOptionsBlock.value;

    optionsBlock.querySelectorAll(dropValue ? 'li' : 'li:not(.selected)').forEach(option => {
        option.remove();
    });

    vanillaOptionsBlock
        .querySelectorAll(dropValue ? 'option' : 'option:not([value="' + vanillaValue + '"])')
        .forEach(option => {
            option.remove();
        });

    if (dropValue) {
        const input = select.querySelector('.input');

        input.querySelector('p').textContent = input.dataset.placeholder;
    }

    for (let index in json) {
        const item = json[index];

        optionsBlock.insertAdjacentHTML(
            'beforeend',
            template
                .replace('{text}', item.text)
                .replace('{value}', item.value)
        );

        vanillaOptionsBlock.insertAdjacentHTML(
            'beforeend',
            vanillaTemplate
                .replace('{text}', item.text)
                .replace('{value}', item.value)
        );
    };
};

const selectInput = () => document.querySelectorAll('.select').forEach(select => {
    select.querySelector('.search-input input')?.addEventListener('input', event => {
        const searchInput = event.currentTarget;
        const query = searchInput.value;
        const asyncUrl = searchInput.dataset.async_url;
        const select = searchInput.closest('.select');
        const options = select.querySelectorAll('.options li');

        if (!asyncUrl) {
            options.forEach(option => {
                option.textContent.toLowerCase().includes(query.toLowerCase())
                    ? option.classList.remove('hidden')
                    : option.classList.add('hidden');
            });

            return;
        }

        asyncSearchForSelectInput(select, searchInput);
    });

    select.querySelector('.search-input input')?.addEventListener("keypress", event => {
        if (event.key === "Enter") {
            event.preventDefault();
        }
    });

    select.querySelector('.search-input .clear')?.addEventListener('click', event => {
        const button = event.currentTarget;
        const searchInput = button.closest('.search-input').querySelector('input');
        const asyncUrl = searchInput.dataset.async_url;
        const options = button.closest('.select').querySelectorAll('.options li');

        searchInput.value = '';

        if (asyncUrl) {
            asyncSearchForSelectInput(select, searchInput);
            return;
        }

        options.forEach(option => {
            option.classList.remove('hidden');
        });
    });
});

const singleSelectInputClickListener = event => {
    showSelectDropdown(event.currentTarget);
};

const singleSelectInputOptionClickListener = event => {
    const option = event.currentTarget;
    const options = option.closest('.options').querySelectorAll('li');
    const select = option.closest('.select');
    const vanillaSelect = select.querySelector('.vanilla select');
    const input = select.querySelector('.input');
    const selectedValue = option.dataset.value;
    const selectedText = option.textContent;

    vanillaSelect.value = selectedValue;
    vanillaSelect.dispatchEvent(new Event('change'));

    options.forEach(option => {
        option.classList.remove('selected');
    });

    option.classList.add('selected');
    input.querySelector('p').textContent = selectedText;
};

const singleSelectInput = () => document.querySelectorAll('.select:not(.multiple)').forEach(select => {
    const input = select.querySelector('.input');

    input.removeEventListener('click', singleSelectInputClickListener);
    input.addEventListener('click', singleSelectInputClickListener);

    select.querySelectorAll('.options li').forEach(option => {
        option.removeEventListener('click', singleSelectInputOptionClickListener);
        option.addEventListener('click', singleSelectInputOptionClickListener);
    });
});

const multiSelectChangeSelected = option => {
    const selectedValue = option.dataset.value;
    const select = option.closest('.select');
    const values = select.querySelector('.values');
    const vanillaSelect = select.querySelector(`.vanilla select`);
    const vanillaSelectOption = vanillaSelect.querySelector(`option[value="${selectedValue}"]`);

    option.classList.toggle('selected');

    vanillaSelectOption.hasAttribute('selected')
        ? vanillaSelectOption.removeAttribute('selected')
        : vanillaSelectOption.setAttribute('selected', 'selected');

    vanillaSelect.dispatchEvent(new Event('change'));

    const selectedValues = [...select.querySelectorAll('.vanilla select option[selected]')]
        .map(option => {
            return {
                value: option.value,
                text: option.text,
            };
        });

    if (selectedValues.length === 0) {
        values.classList.add('empty');

        return;
    }

    const renderedOptions = selectedValues.map(
        item => select
            .querySelector('.design')
            .innerHTML
            .replace('{value}', item.value)
            .replace('{text}', item.text)
    ).join('\n');

    values.innerHTML = renderedOptions;
    values.classList.remove('empty');

    values.querySelectorAll('li svg').forEach(value => {
        value.addEventListener('click', event => {
            const button = event.currentTarget;
            const value = button.closest('li').dataset.value;
            const option = button.closest('.select').querySelector(`.options li[data-value="${value}"]`);

            multiSelectChangeSelected(option);
        });
    });
};

const multiSelectInput = () => document.querySelectorAll('.select.multiple').forEach(select => {
    select.querySelector('.input > svg').addEventListener('click', event => {
        showSelectDropdown(event.currentTarget.closest('.input'));
    });

    select.querySelectorAll('.options li').forEach(option => {
        option.addEventListener('click', event => {
            multiSelectChangeSelected(event.currentTarget);
        });
    });

    select.querySelectorAll('.values li').forEach(option => {
        option.addEventListener('click', event => {
            multiSelectChangeSelected(event.currentTarget);
        });
    });
});

const submenu = () => document.querySelectorAll('menu > li.has-submenu').forEach(button => {
    button.querySelector('a').addEventListener('click', event => {
        event.preventDefault();

        const link = event.currentTarget;
        const button = link.closest('li');

        button.classList.toggle('shown');
    });
});

const tableActionsShortcuts = () => {
    document
        .querySelectorAll('[href="#hush-table-row-add"]')
        .forEach(button => {
            if (button.dataset.hasTableRowAddListener) {
                return;
            }

            button.dataset.hasTableRowAddListener = true;

            button.addEventListener('click', event => {
                event.preventDefault();

                const button = event.currentTarget;
                const target = document.querySelector(button.dataset.target);
                const template = button.dataset.template;

                const uniqueId = Date.now().toString(36) + Math.random().toString(36).substr(2);

                target.insertAdjacentHTML('beforeend', template.replace(new RegExp('{id}', 'g'), uniqueId));

                init();
            })
        });

    document
        .querySelectorAll('[href="#hush-table-row-drop"]')
        .forEach(button => button.addEventListener('click', event => {
            event.preventDefault();

            const button = event.currentTarget;
            const row = button.closest('tr');

            row.remove();
        }));
};

const init = () => {
    closeModal();
    openAsyncModal();
    pageReload();

    fileInput();
    fileMultipleInput();
    fileMultipleInputDeleteActionInit();
    fileMultipleInputMoveActionInit();

    searchInput();
    slugInput();

    selectInput();
    singleSelectInput();
    multiSelectInput();

    tableActionsShortcuts();
};

submenu();
init();
