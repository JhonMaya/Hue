<?php exit() ?>--by vadash 108.162.254.25
success, all = 0.0, 0.0
for i, g in pairs(_G) do
    if type(g) == "function" then
        dif = tonumber(string.sub(tostring(_G.GetUser), 11), 16) -
            tonumber(string.sub(tostring(g), 11), 16)
        if dif < 50000 and dif > -50000 then
            if dif > 777 or dif < -777 then
                success = success + 1
            end
        end
        all = all + 1
    end
end
print(success/all)