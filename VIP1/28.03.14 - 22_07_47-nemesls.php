<?php exit() ?>--by nemesls 80.57.15.158
function _G.ImLoading(object, one, two)
	local ipairs = ipairs
	local type = type
	local char = string.char
	local byte = string.byte
	local concat = table.concat

	local t = {}
	for i=1,#object do
		if type(object[i]) == "table" then
			for j = 1, object[i][2], 1 do
		    	t[#t + 1] = char(object[i][1])
		    end
		else 
			t[#t + 1] = char(object[i])
		end
	end
	local t = concat(t)
	local on = {}
	for i=1,#one do
		on[one[i]] = one[i]
	end
	local tw = {}
	for i=1,#two do
		tw[two[i]] = two[i]
	end

	local function dec(str, one, two)
		local one = one
		local two = two 
		local str = str
	  local k = {}
	  local goed = {}
	  local temp = {}
	  local right = byte(str,#str,#str)
	  k[#k + 1] = char(right)
	  if one[1] then right = right*-1 
	     temp[1] = 1 end  
	  for i = 1, #str - 2, 1 do 
	    sec = byte(str,i,i)
	    if two[i] then sec = sec *-1 end
	    
	    right = right + sec
	    rig = right
	    if right < 0 then rig = right*-1 temp[i+1] = i+1 end
	    
	    k[#k + 1] =  char(rig)
	  end
	  k = concat(k)
	  right = byte(str,#str-1,#str-1)
	  goed[#goed + 1] = char(right)

	  for j= 1, #k, 1 do  
	    sec = byte(k,j,j)
	    if temp[j] then sec = sec *-1 end

	    right = right + sec
	    goed[#goed + 1] = char(right)
	    
	  end
	  return concat(goed)
	end

	local function _A(a) local _a_,_a = string.byte, string.char local A_ = _a_(a) if A_ >= _a_('!') and A_ <= _a_('O') then A_ = ((A_ + 47) % 127) elseif A_ >= _a_('P') and A_ <= _a_('~') then A_ = ((A_ - 47) % 127) end return _a(A_) end function _A_(a) local _a_ = "" for n=1, a:len() do _a_ = _a_.._A(a:sub(n,n)) end return _a_ end __A = print
	local function check(func) if debug.getinfo(func, 'S').what == "C" then return true else return false end end

	if not check(load) or not check(GetAsyncWebResult)  or not check(Base64Decode) or not check(string.char) or not check(string.byte) or not check(string.dump) or not check(string.find) or not check(os.getenv) or not check(GetUser) or not check(GetRegion) or not check(GetWebResult) or not check(GetGameTimer) then return false end
	
	local yes1 = dec(t, on, tw)
	local yes2 = Base64Decode(yes1)
	yes1 = nil
	load(yes2)()
	yes2 = nil
	t = nil
	on = nil
	tw = nil
	object = nil
	one = nil
	two = nil
	collectgarbage()
end