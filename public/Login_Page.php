<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <link rel="stylesheet" href="../src/output.css">
</head>

<body>
    <div class="flex flex-row w-full">
        <div class="flex w-1/3 px-20 ">

            <div class="w-full ">
                <h1 class=" text-[#E62029] items-center align-middle text-5xl py-14 mb-20 font-bold ">My<span
                        class=" text-[#006CB7]">SPBU</span></h1>
                <p class="mb-4 text-slate-500">
                    Welcome Back! Please enter your detail
                </p>
                <form action="login.php" method="post">
                    <!-- username dan pass -->
                    <div class="mt-5">
                        <span>Username</span>
                        <input name="username" id="username" type="text" placeholder="Masukan Username "
                            class="border rounded-md border-gray-400 py-1 h-12 px-2 w-full hover:border-blue-800">
                    </div>
                    <div class="mt-5">
                        <span>Password</span>
                        <input type="password" name="password" id="password" placeholder="Masukan Password"
                            class="border rounded-md border-gray-400 py-1 h-12 px-2 w-full hover:border-blue-800">
                    </div>
                    <a href="#"
                        class="underline underline-offset-1 text-blue-400 hover:text-blue-800 text-xs font-semibold">Forgot
                        Password?</a>
                    <!-- Button login -->
                    <div class="mt-5">
                        <button type="submit" name="submit"
                            class="hover:bg-[#00ADB5] rounded-md w-full bg-[#E62029] py-3 text-center text-white">Login</button>
                    </div>
                </form>
                <p class="text-xs  ">dont,have account? <a class="text-blue-400 hover:text-blue-300" href="">Sign Up</a>
                </p>
                <!-- <div class="mt-5 border-2 rounded-md border-gray-400 py-2  scale-100  px-2">
                <h4  class=" w-full hover:text-[#00ADB5]">Continue With Google</h4>
                <img class="w-1/12"  src="/images/google.png" alt="">
              </div> -->

            </div>

        </div>

        <div class="bg-black flex w-2/3">
            <img class="h-screen  flex w-full items-end" src="../img/loginn.jpg" alt="">
        </div>
    </div>








</body>

</html>