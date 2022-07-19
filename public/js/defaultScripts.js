const handleError = data => {
	response = data.responseJSON;

	message = '';
	if (response && response.errors) {
		response.errors.forEach(err => {
			message += err + '<br>';
		});
	} else {
		message = 'Erro Inesperado';
	}

	Swal.fire({
		title: 'Erro',
		html: message,
		icon: 'error',
	});
};

const setupDocument = options => {
	const { resource, path, formId, btnNewId, btnEditId, btnDeleteId, modelId, setupFieldValues, emptyFields } = options;

	$(formId).submit(function (e) {
		e.preventDefault();

		var form = $(this);
		var id = $('#id').val();

		var validator = form.validate();
		if (validator.form()) {
			$.ajax({
				type: id ? 'PUT' : 'POST',
				url: id ? path + '/' + id : path,
				data: form.serialize(),
				dataType: 'JSON',
				success: function (data) {
					Swal.fire({
						title: 'Sucesso',
						text: resource + (id ? ' atualizado(a)' : ' cadastrado(a)') + ' com sucesso',
						icon: 'success',
					}).then(() => {
						location.reload();
					});
				},
				error: function (data) {
					handleError(data);
				},
			});
		}
	});

	$(btnNewId).on('click', function () {
		var validator = $(formId).validate();
		validator.resetForm();

		emptyFields();
		$(modelId).modal('show');
	});

	$(document).on('click', btnEditId, function () {
		var validator = $(formId).validate();
		validator.resetForm();

		var id = $(this).attr('data-id');

		$.ajax({
			url: path + '/' + id,
			type: 'GET',
			dataType: 'JSON',
			success: function (data) {
				data.id = id;
				setupFieldValues(data);
				$(modelId).modal('show');
			},
			error: function (data) {
				handleError(data);
			},
		});
	});

	$(document).on('click', btnDeleteId, function () {
		var id = $(this).attr('data-id');

		Swal.fire({
			title: 'Confirma a exclusão da ' + resource + '?',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			cancelButtonText: 'Cancelar',
			confirmButtonText: 'Confirma Exclusão',
		}).then(result => {
			if (result.isConfirmed) {
				$.ajax({
					url: path + '/' + id,
					type: 'DELETE',
					dataType: 'JSON',
					success: function (data) {
						Swal.fire({
							title: 'Sucesso',
							text: resource + ' excluido(a) com sucesso',
							icon: 'success',
						}).then(() => {
							location.reload();
						});
					},
					error: function (data) {
						handleError(data);
					},
				});
			}
		});
	});
};
