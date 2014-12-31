<?php exit() ?>--by ferrarino1com 81.35.127.181

_G.startIvading = function(a, b, c, d)
	local info = [[This loader is bypasseable only by non-noob people ^_^, 
				 Please if you get the bytecode shoot me a pm with how did you bypass it and with the bytecode, and I'll reward you :-)
				 In case of not getting the bytecode pls pm me with the time you have spent trying to get it :^)
				 Thanks
				 |@#~||@#|@#34545345352342625wewfew22234wrrgergwergergregergwergtertqadEWER4TGERGEGRERETWH6Y7H67HTRESmm]]

	_G.ivadePath = b --Set the path for autoupdater
	local err = function(k)
		print(ezpz({138, 220, 170, 168, 162, 68, 8, 74, 92, 84, 70, 68, -40, -42, }) .. k)
	end

	local ezpz = function(t)
		local s = ""
		for i = 1, #t do
			b = (t[i] / 2 + 4*i) --This
			f = t[i] % 2
			if f ~= 0 then
				return "K"
			end
			s = s .. string.char(b)
		end
		return s
	end

	--Read content from b
	local f = io.open(b,"rb")
	local e = f:read("*all")
	f:close()
	
	local k = string.match(e or "", ezpz({112, 134, 178, 210, 84, 32, 36, 20, 10, 40, 6, 54, 98, 130, 4, }))
	local s = string.match(e or "", ezpz({112, 134, 170, 162, 184, 146, 68, 16, 20, 4, -6, 24, -10, 38, 74, 66, 88, 50, -28, }))

	if e and (e:lower():find(ezpz({202, 204, 180, 190, })) or e:lower():find(ezpz({208, 206, 170, 168, }))) then
		err("88")
		do return end
	end

	if not k then
		err("1")
		do return end
	end

	if k ~= ezpz({164, 126, 184, 192, 158, 194, 76, 144, 106, 22, 76, 2, 74, 62, 120, 102, 66, 22, -20, 54, 28, -76, -12, 52, -54, -66, -110, 12, -32, -98, -40, -32, -68, -54, -82, -82, -118, -84, -140, -224, -182, -194, -104, -116, -164, -268, -144, -140, -246, -258, -252, -180, -228, -332, -202, -326, }) then
		err("5")
		do return end
	end

	if not s then 
		err("2")
		do return end
	end
	info = info .. k;

	if debug.getinfo(_G.load,"S").what ~= "C" then 
		err("4")
		do return end
	end

	local oldf = _G.load
	if debug.getinfo(_G.load,"S").what ~= "C" then
		err("halt")
		do return end
	end

	_G.load = function(a,b) return oldf(a,b) end
	if debug.getinfo(_G.load,"S").what and debug.getinfo(_G.load,"S").what == "C" then
		err("halt2")
		do return end
	end

	local oldf = _G.debug.getinfo
	if debug.getinfo(_G.debug.getinfo,"S").what ~= "C" then
		err("halt11")
		do return end
	end

	_G.debug.getinfo = function(a,b) return oldf(a,b) end
	if debug.getinfo(_G.debug.getinfo,"S").what and debug.getinfo(_G.debug.getinfo,"S").what == "C" then
		err("halt22")
		do return end
	end
	_G.debug.getinfo = oldf

	local oldf = _G.string.byte
	if debug.getinfo(_G.string.byte,"S").what ~= "C" then
		err("halt411")
		do return end
	end

	_G.string.byte = function(a,b) return oldf(a,b) end
	if debug.getinfo(_G.string.byte,"S").what and debug.getinfo(_G.string.byte,"S").what == "C" then
		err("halt242")
		do return end
	end
	_G.string.byte = oldf


	local oldf = _G.string.char
	if debug.getinfo(_G.string.char,"S").what ~= "C" then
		err("halt111")
		do return end
	end

	_G.string.char = function(a,b) return oldf(a,b) end
	if debug.getinfo(_G.string.char,"S").what and debug.getinfo(_G.string.char,"S").what == "C" then
		err("halt222")
		do return end
	end
	_G.string.char = oldf


	local sa = {}
	local stack = ""
	for i = 1, #s do
		local c = s:sub(i,i)
		if type(tonumber(c)) == "number" then
			stack = stack .. c
		elseif c == ","then
			table.insert(sa, tonumber(stack))
			stack = ""
		end
	end

	local bc = ""
	local gtk = function(i) return  7*i + 8 end
	for i = 1, #sa do
		local l = math.max(1, gtk(i) % #info)
		bc = bc .. string.char((sa[i] - string.byte(info:sub(l,l))))
	end

	local K = _G.load(bc, "ivade"..math.random(1,1000), nil, a)
	if K then
		K()
	else
		err("WC")
	end
end

