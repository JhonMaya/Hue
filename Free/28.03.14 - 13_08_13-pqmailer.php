<?php exit() ?>--by pqmailer 217.82.31.54
for db,_c in pairs(_G)do if math.random(1,10)==2 then

if

debug.getinfo(GetTickCount,"S").what~="C"then print("rip 1")return end end

if

math.random(1,10)==7 then if debug.getinfo(CastItem,"S").what~="Lua"then

print("rip 2")return end end

if type(_c)=="function"then if debug.getinfo(_c,"f").func~=_c then

print("rip 3")return end;

if debug.getinfo(debug.getinfo,"S").what ~="C" then return end;

if

debug.getinfo(_c,"S").what=="C"then nC=nC+1 end end end

if nC<148 -math.random(10,15)or

nC>148 +math.random(10,15)then print("rip 4")return end;if debug.getinfo(_G.io.open,"S").what~="C"then

print("rip 5")return end

if

debug.getinfo(_G.load,"S").what~="C"then print("rip 6")return end;if

debug.getinfo(_G.Base64Decode,"S").what~="C"then print("rip 7")return end

function _G._InjectFreelo(cipher, env)
 local str = Base64Decode(cipher)
 cipher = nil
 k = load(str, nil, "bt", env)
 str = nil 
 k()
 k = nil
end 