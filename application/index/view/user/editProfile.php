<div class="box">
	<div class="box-body">
		
		<div class="row">
			<div class="form-group">
				<label for="userName" class="col-sm-2 col-lg-2 control-label">通行证登录用户名 / UserName</label>
				<div class="col-sm-10 col-lg-10">
					<input class="form-control" ref="userName" v-bind:value="userInfo['userName']">
				</div>
			</div>
		</div><br>
		
		<div class="row">
			<div class="form-group">
				<label for="nickName" class="col-sm-2 col-lg-2 control-label">通行证昵称 / NickName</label>
				<div class="col-sm-10 col-lg-10">
					<input class="form-control" ref="nickName" v-bind:value="userInfo['nickName']">
				</div>
			</div>
		</div><br>

		<div class="row">
			<div class="form-group">
				<label for="phone" class="col-sm-2 col-lg-2 control-label">手机号码 / Phone</label>
				<div class="col-sm-10 col-lg-10">
					<input type="number" class="form-control" ref="phone" v-bind:value="userInfo['phone']">
				</div>
			</div>
		</div><br>

		<div class="row">
			<div class="form-group">
				<label for="email" class="col-sm-2 col-lg-2 control-label">邮箱地址 / Email</label>
				<div class="col-sm-10 col-lg-10">
					<input type="email" class="form-control" ref="email" v-bind:value="userInfo['email']">
				</div>
			</div>
		</div>

		<hr>
		
		<button class="btn btn-primary" style="width:48%" v-on:click="cancel">&lt; 取 消 操 作</button> <button class="btn btn-success" style="width:48%" v-on:click="updateUserInfo">确 认 修 改 &gt;</button>
		
	</div>
</div>